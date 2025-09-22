<?php

namespace App\Services;

require_once base_path('Mpesa/PortalSDK/api.php');
require_once base_path('Mpesa/Crypt/RSA.php');

use \APIContext;
use \APIRequest;
use \APIMethodType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class MpesaService
{
    private array $config;
    private string $apiKey;
    private string $publicKey;
    private string $baseUrl;
    private string $sessionPath;
    private string $c2bPaymentPath;
    private string $serviceProviderCode;
    private string $country;
    private string $currency;
    private int $timeout;
    private int $sessionCacheDuration;
    private string $environment;

    public function __construct()
    {
        $this->config = config('mpesa');
        $this->apiKey = $this->config['api_key'];
        $this->publicKey = $this->config['public_key'];
        
        $baseUrl = $this->config['base_url'];
        $baseUrl = preg_replace('#^https?://#', '', $baseUrl);
        $this->baseUrl = 'https://' . $baseUrl;
        
        $this->sessionPath = $this->config['session_path'];
        $this->c2bPaymentPath = $this->config['c2b_payment_path'];
        $this->serviceProviderCode = $this->config['service_provider_code'];
        $this->country = $this->config['country'];
        $this->currency = $this->config['currency'];
        $this->timeout = $this->config['timeout'];
        $this->sessionCacheDuration = $this->config['session_cache_duration'];
        $this->environment = $this->config['environment'] ?? 'sandbox';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->publicKey) && !empty($this->serviceProviderCode);
    }

    private function getSessionId(): ?string
    {
        $cacheKey = 'mpesa_session_id';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $context = new APIContext();
            $context->set_api_key($this->apiKey);
            $context->set_public_key($this->publicKey);
            $context->set_ssl(true);
            $context->set_method_type(APIMethodType::GET);
            $context->set_address(preg_replace('#^https?://#', '', $this->baseUrl));
            $context->set_port(443);
            $context->set_path($this->sessionPath);
            $context->add_header('Origin', '*');

            $request = new APIRequest($context);
            $response = $request->execute();

            if (!$response || $response->get_body() == null) {
                Log::error('MpesaService: getSessionId call failed - no response body');
                return null;
            }

            $decoded = json_decode($response->get_body());
            
            if (isset($decoded->output_SessionID)) {
                $sessionId = $decoded->output_SessionID;
                Cache::put($cacheKey, $sessionId, $this->sessionCacheDuration);
                Log::info('Mpesa session ID retrieved and cached successfully.', ['session_id' => $sessionId]);
                return $sessionId;
            }
            
            Log::error('MpesaService: Failed to get session ID', (array)$decoded);
            return null;

        } catch (Exception $e) {
            Log::error('MpesaService: Exception in getSessionId', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    public function processC2BPayment(array $paymentData): array
    {
        $sessionId = $this->getSessionId();
        if (!$sessionId) {
            return ['success' => false, 'error' => 'Could not retrieve M-Pesa session ID'];
        }

        try {
            $context = new APIContext();
            $context->set_api_key($sessionId);
            $context->set_public_key($this->publicKey);
            $context->set_ssl(true);
            $context->set_method_type(APIMethodType::POST);
            $context->set_address(preg_replace('#^https?://#', '', $this->baseUrl));
            $context->set_port(443);
            $context->set_path($this->c2bPaymentPath);
            $context->add_header('Origin', '*');

            $amountStr = number_format($paymentData['amount'], 2, '.', '');

            $customerMsisdn = ($this->environment === 'sandbox') 
                ? '000000000001' 
                : $paymentData['phone_number'];

            Log::info('Using Customer MSISDN for C2B Payment', ['msisdn' => $customerMsisdn, 'environment' => $this->environment]);

            $context->add_parameter('input_Amount', $amountStr);
            $context->add_parameter('input_Country', $this->country);
            $context->add_parameter('input_Currency', $this->currency);
            $context->add_parameter('input_CustomerMSISDN', $customerMsisdn);
            $context->add_parameter('input_ServiceProviderCode', $this->serviceProviderCode);
            $context->add_parameter('input_ThirdPartyConversationID', $paymentData['transaction_id']);
            $context->add_parameter('input_TransactionReference', $paymentData['reference']);
            $context->add_parameter('input_PurchasedItemsDesc', $paymentData['description']);

            $request = new APIRequest($context);
            $response = $request->execute();

            if (!$response || $response->get_body() == null) {
                throw new Exception('No response from M-Pesa API');
            }

            $decoded = json_decode($response->get_body());

            Log::info('Mpesa C2B Payment Response Body', ['response' => $decoded]);

            return ['success' => true, 'data' => $decoded];

        } catch (Exception $e) {
            Log::error('M-Pesa C2B payment failed', ['error' => $e->getMessage(), 'data' => $paymentData]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function validatePhoneNumber(string $phoneNumber): ?string
    {
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);

        if (preg_match('/^(?:\+266|266)(5|6)\d{7}$/', $phoneNumber)) {
            $phoneNumber = ltrim($phoneNumber, '+');
            if (!str_starts_with($phoneNumber, '266')) {
                return '266' . substr($phoneNumber, -8);
            }
            return $phoneNumber;
        }

        return null;
    }

    public function generateTransactionId(): string
    {
        return 'MP' . now()->format('YmdHis') . Str::upper(Str::random(4));
    }

    public function processCallback(array $callbackData): array
    {
        $transactionId = $callbackData['input_ThirdPartyConversationID'] ?? null;
        $status = ($callbackData['output_ResponseCode'] ?? 'INS-1') === 'INS-0' ? 'success' : 'failed';

        return [
            'transaction_id' => $transactionId,
            'status' => $status,
            'raw_data' => $callbackData
        ];
    }
}
