<?php
namespace Wezeo\Notion\Http\Controllers;

use Backend\Classes\Controller;
use October\Rain\Network\Http;

class NotionController extends Controller
{
    public function index()
    {
        //TODO: De-Hardcode
        $res = Http::post("https://api.notion.com/v1/databases/" . getenv('NOTION_DB') . "/query", function ($http) {
            $taskName = 'applicant-detail* update (save) @be';

            $http->header('Notion-Version', '2022-06-28');
            $http->header('accept', 'application/json');
            $http->header('content-type', 'application/json');
            $http->header('Authorization', 'Bearer ' . getenv('NOTION_INTEGRATION_TOKEN'));

            $body = [
                "filter" => [
                    "property" => "Task",
                    "rich_text" => [
                        "contains" => $taskName
                    ]
                ]
            ];

            $http->setOption(CURLOPT_POSTFIELDS, json_encode($body));
        });

        $decodedRes = json_decode($res->body, true);
        $properties = $decodedRes['results'][0]['properties'];

        return response($properties)
            ->header('Content-Type', 'application/json');
    }

    public function update()
    {
        //TODO: De-Hardcode
        $res = Http::post("https://api.notion.com/v1/pages/", function ($http) {
            $taskName = 'test task e';

            $http->header('Notion-Version', '2022-06-28');
            $http->header('accept', 'application/json');
            $http->header('content-type', 'application/json');
            $http->header('Authorization', 'Bearer ' . getenv('NOTION_INTEGRATION_TOKEN'));

            $body = [
                "parent" => [
                    "type" => "database_id",
                    "database_id" => getenv('NOTION_DB')
                ],
                "properties" => [
                    "Task" => [
                        "title" => [
                            [
                                "text" => [
                                    "content" => $taskName
                                ]
                            ]
                        ]
                    ],
                    "Category" => [
                        "rich_text" => [
                            [
                                "text" => [
                                    "content" => "applicant-detail*"
                                ]
                            ]
                        ]
                    ],
                ]
            ];
            $http->setOption(CURLOPT_POSTFIELDS, json_encode($body));
        });

        return response($res->body)
            ->header('Content-Type', 'application/json');
    }

    public function delete()
    {
        //TODO: De-Hardcode
        $res = Http
            ::delete("https://api.notion.com/v1/blocks/0a7199579fdf4de09c7ad0f4b3e4140c", function ($http) {
                $taskName = 'test task';

                $http->header('Notion-Version', '2022-06-28');
                $http->header('accept', 'application/json');
                $http->header('content-type', 'application/json');
                $http->header('Authorization', 'Bearer ' . getenv('NOTION_INTEGRATION_TOKEN'));
            });

        return response($res->body)
            ->header('Content-Type', 'application/json');
    }
}
