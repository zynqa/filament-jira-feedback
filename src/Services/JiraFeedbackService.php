<?php

declare(strict_types=1);

namespace Zynqa\FilamentJiraFeedback\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class JiraFeedbackService
{
    protected Client $client;

    protected string $baseUrl;

    protected string $email;

    protected string $apiToken;

    protected string $projectKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('filament-jira-feedback.jira.url'), '/');
        $this->email = config('filament-jira-feedback.jira.email');
        $this->apiToken = config('filament-jira-feedback.jira.api_token');
        $this->projectKey = config('filament-jira-feedback.jira.project_key');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => [$this->email, $this->apiToken],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /**
     * Create a new issue in Jira.
     *
     * @param  array<string, mixed>  $issueData
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function createIssue(array $issueData): array
    {
        try {
            // Convert description to ADF format if it's plain text
            if (isset($issueData['description']) && is_string($issueData['description'])) {
                $issueData['description'] = $this->convertToAdf($issueData['description']);
            }

            $response = $this->client->post('rest/api/3/issue', [
                'json' => [
                    'fields' => $issueData,
                ],
            ]);

            $body = (string) $response->getBody();
            $result = json_decode($body, true);

            if (! is_array($result)) {
                throw new Exception('Invalid JSON response from Jira API');
            }

            return $result;
        } catch (GuzzleException $e) {
            Log::error('Jira API error', [
                'message' => $e->getMessage(),
                'issue_data' => $issueData,
            ]);

            throw new Exception('Failed to create Jira issue: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Convert plain text to Atlassian Document Format (ADF).
     *
     * @return array<string, mixed>
     */
    protected function convertToAdf(string $text): array
    {
        // Split text into paragraphs
        $paragraphs = preg_split('/\n\n+/', $text) ?: [$text];

        $content = [];

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (empty($paragraph)) {
                continue;
            }

            // Split paragraph into lines for line breaks
            $lines = explode("\n", $paragraph);
            $textContent = [];

            foreach ($lines as $index => $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                $textContent[] = [
                    'type' => 'text',
                    'text' => $line,
                ];

                // Add hard break between lines (except for the last line)
                if ($index < count($lines) - 1) {
                    $textContent[] = [
                        'type' => 'hardBreak',
                    ];
                }
            }

            if (! empty($textContent)) {
                $content[] = [
                    'type' => 'paragraph',
                    'content' => $textContent,
                ];
            }
        }

        return [
            'version' => 1,
            'type' => 'doc',
            'content' => $content,
        ];
    }

    /**
     * Validate Jira configuration.
     */
    public function validateConfiguration(): bool
    {
        return ! empty($this->baseUrl)
            && ! empty($this->email)
            && ! empty($this->apiToken)
            && ! empty($this->projectKey);
    }

    /**
     * Get the configured project key.
     */
    public function getProjectKey(): string
    {
        return $this->projectKey;
    }
}
