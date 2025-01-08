<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class TinyAPI //Doc: https://tiny.com.br/api-docs/
{
    public function TinyRequest($endPoint, $queryString = null)
    {
        $client = new Client();

        // Construção da URL com segurança para query strings
        $query = http_build_query(['token' => env('TOKEN'), 'formato' => 'JSON']);

        if ($queryString) {
            $query = $query . $queryString;
        }

        $url = env("URL") . "$endPoint?" . $query;

        try {
            $response = $client->request('POST', $url);
            $body = $response->getBody()->getContents();
            $status = $response->getStatusCode();

            $decodedBody = json_decode($body);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ["status" => 500, "erro" => "Erro ao decodificar a resposta JSON"];
            }

            if (isset($decodedBody->retorno->codigo_erro)) {
                switch ($decodedBody->retorno->codigo_erro) {
                    case "1":
                        return ["status" => 401, "erro" => "Token inválido"];
                    case "32":
                        return ["status" => 401, "erro" => "Produto não localizado"];
                    default:
                        return ["status" => 401, "erros" => $decodedBody->retorno->registros->registro->erros ?? $decodedBody];
                }
            }

            return [
                "status" => $status,
                "body" => $decodedBody
            ];
        } catch (Exception $e) {
            return ["status" => 500, "erro" => "Falha na requisição: " . $e->getMessage()];
        }
    }
}
