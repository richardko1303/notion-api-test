<?php
namespace Wezeo\Notion\Http\Controllers;

use Backend\Classes\Controller;
use October\Rain\Network\Http;
use Log;

class NotionController extends Controller
{
    public function receive()
    {
        $url = post('url');
        $taskName = post('task_name');
        $assign_id = post('assign_id');
        $category = post('category');
        $progress = post('progress');
        $dateStart = post('date_start');

        // Tu uz mozeme zapisovat do WGridu

    }
    public function index()
    {
        $res = $this->getFromApi(post('task_name'));

        $decodedRes = json_decode($res->body, true);
        //$properties = $decodedRes['results'][0]['properties'];

        return response($decodedRes)
            ->header('Content-Type', 'application/json');
    }

    public function update()
    {
        $findPage = $this->getFromApi(post('find_task_name'));
        $pageID = json_decode($findPage->body, true)['results'][0]['id'];

        $res = Http::patch("https://api.notion.com/v1/pages/" . $pageID, function ($http) {
            $taskName = post('task_name');
            $categoryName = post('category_name');
            $status = post('status');
            $dueDate = post('due_date');
            $estHours = intval(post('est_hours'));
            $assignName = post('assign_name');

            $http->header('Notion-Version', '2022-06-28');
            $http->header('accept', 'application/json');
            $http->header('content-type', 'application/json');
            $http->header('Authorization', 'Bearer ' . getenv('NOTION_INTEGRATION_TOKEN'));

            $assignID = $this->getUserIdFromName($assignName);

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
                    "Assign" => [
                        "type" => "people",
                        "people" => [
                            [
                                "object" => "user",
                                "id" => $assignID
                            ]
                        ]
                    ],
                    "Status" => [
                        "status" => [
                            "name" => $status
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
                    "Date" => [
                        "date" => [
                            "start" => $dueDate
                        ]
                    ],
                    "Est. hours" => [
                        "number" => $estHours
                    ],
                    "Progress" => [
                        "number" => 0
                    ],

                ]
            ];

            $http->setOption(CURLOPT_POSTFIELDS, json_encode($body));
        });

        return response($res->body)
            ->header('Content-Type', 'application/json');
    }

    public function create()
    {
        $res = Http::post("https://api.notion.com/v1/pages/", function ($http) {
            $taskName = post('task_name');
            $categoryName = post('category_name');
            $status = post('status');
            $dueDate = post('due_date');
            $estHours = intval(post('est_hours'));
            $assignName = post('assign_name');

            $http->header('Notion-Version', '2022-06-28');
            $http->header('accept', 'application/json');
            $http->header('content-type', 'application/json');
            $http->header('Authorization', 'Bearer ' . getenv('NOTION_INTEGRATION_TOKEN'));

            $assignID = $this->getUserIdFromName($assignName);
            Log::info($assignID);

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
                    "Assign" => [
                        "type" => "people",
                        "people" => [
                            [
                                "object" => "user",
                                "id" => $assignID
                            ]
                        ]
                    ],
                    "Status" => [
                        "status" => [
                            "name" => $status
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
                    "Date" => [
                        "date" => [
                            "start" => $dueDate
                        ]
                    ],
                    "Est. hours" => [
                        "number" => $estHours
                    ],
                    "Progress" => [
                        "number" => 0
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
        } catch (\Exception $e) {
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

    public function user()
    {
        $user = $this->getUserIdFromName(post('user_name'));

        return response($user)
            ->header('Content-Type', 'application/json');
    }

    /* GET FUNCTIONS */

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

    private function getUserIdFromName($input)
    {
        $res = Http
            ::get(
                "https://api.notion.com/v1/users/",
                function ($http) {

                    $http->header('Notion-Version', '2022-06-28');
                    $http->header('Authorization', 'Bearer ' . getenv('NOTION_INTEGRATION_TOKEN'));
                }
            );

        $decoded = json_decode($res->body, true);
        $userID = 0;

        for ($i=0; $i < count($decoded['results']); $i++) {
            if ($decoded['results'][$i]['name'] === $input) {
                $userID = $decoded['results'][$i]['id'];
            }
        }

        if ($userID === 0) {
            for ($i=0; $i < count($decoded['results']); $i++) {
                if ($decoded['results'][$i]['id'] === $input) {
                    $userID = $decoded['results'][$i]['id'];
                }
            }
        }

        return $userID;
    }
}
