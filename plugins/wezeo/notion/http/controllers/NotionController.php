<?php
namespace Wezeo\Notion\Http\Controllers;

use Backend\Classes\Controller;
use October\Rain\Network\Http;
use Log;

class NotionController extends Controller
{
    public function index()
    {
        $res = $this->getFromApi(post('task_name'));

        $decodedRes = json_decode($res->body, true);
        //$properties = $decodedRes['results'][0]['properties'];

        return response($decodedRes)
            ->header('Content-Type', 'application/json');
    }

    public function create()
    {
        $res = Http::post("https://api.notion.com/v1/pages/", function ($http) {
            $taskName = post('task_name');
            $categoryName = post('category_name');

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
                                    "content" => $categoryName
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
        try {
            $block = $this->getFromApi(post('task_name'));
            $blockID = json_decode($block->body, true)['results'][0]['id'];
        } catch(\Exception $e) {
            return response($e->getMessage())
                ->header('Content-Type', 'application/json');
        }

        $res = Http
            ::delete("https://api.notion.com/v1/blocks/" . $blockID, function ($http) {
                $http->header('Notion-Version', '2022-06-28');
                $http->header('accept', 'application/json');
                $http->header('content-type', 'application/json');
                $http->header('Authorization', 'Bearer ' . getenv('NOTION_INTEGRATION_TOKEN'));
            });

        return response($res->body)
            ->header('Content-Type', 'application/json');
    }

    private function getFromApi($taskName)
    {
        $res = Http
            ::post(
                "https://api.notion.com/v1/databases/" . getenv('NOTION_DB') . "/query",
                function ($http) use ($taskName) {

                    Log::info($taskName);

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
                }
            );

        return $res;
    }
}
