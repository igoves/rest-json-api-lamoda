<?php

class Core
{

    private $db;

    public function __construct()
    {
        $this->db = new MongoDB\Driver\Manager('mongodb://localhost:27017');
        $do = isset($_GET['do']) && !empty($_GET['do']) ? strip_tags($_GET['do']) : '';

        // routes
        if ($do === 'containers') {
            if ($postData = file_get_contents('php://input')) {
                $this->containersAdd($postData);
            }
            $this->containersList();
        } elseif ($do === 'products') {
            $this->products();
        }
    }

    private function containersAdd($postData) : string
    {

        // check valid json
        if (Helper::isJson($postData) != 1) {
            Helper::showContent(json_encode([
                'error' => 'Sorry data is not valid'
            ]));
        }

        $data = json_decode($postData, true);

        // check valid post data
        if (!isset($data['name'])) {
            Helper::showContent(json_encode([
                'error' => 'Sorry, container name is required'
            ]));
        } elseif (!isset($data['products'])) {
            Helper::showContent(json_encode([
                'error' => 'Sorry, container must include products'
            ]));
        } elseif (!isset($data['products'][0]['name'], $data['products'][0]['id'])) {
            Helper::showContent(json_encode([
                'error' => 'Sorry, container must include products with name and id'
            ]));
        }

        // insert data
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($data);
        try {
            $this->db->executeBulkWrite('lamoda.containers', $bulk);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            Helper::showContent(json_encode(['error' => $e->getMessage()]));
        }

        Helper::showContent(json_encode([
            'success' => 'Container added'
        ]));

    }

    public function containersList() : string
    {
        $cursor = '';
        $filter = [];
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $filter = ['_id' => new MongoDB\BSON\ObjectId($_GET['id'])];
        }
        $query = new MongoDB\Driver\Query($filter, []);
        try {
            $cursor = $this->db->executeQuery('lamoda.containers', $query);
            $cursor = json_encode($cursor->toArray());
        } catch (MongoDB\Driver\Exception\Exception $e) {
            Helper::showContent(json_encode(['error' => $e->getMessage()]));
        }

        Helper::showContent($cursor);
    }


    private function products() : string
    {

        $command = new MongoDB\Driver\Command([
            'aggregate' => 'containers',
            'pipeline' => [
                ['$unwind' => '$products'],
                ['$group' => [
                    '_id' => [
                        'p_name' => '$products.name',
                        'p_id' => '$products.id'
                    ],
                    'entry' => [
                        '$push' => [
                            'c_id' => '$_id',
                            'c_name' => '$name',
                        ],
                    ],
                ],
                ],
            ],
            'cursor' => new stdClass,
        ]);

        try {
            $cursor = $this->db->executeCommand('lamoda', $command);
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            Helper::showContent(json_encode(['error' => $e->getMessage()]));
        }

        $data = [];
        foreach ($cursor as $document) {
            $document = json_decode(json_encode($document), true);
            $c_id = $document['entry'][0]['c_id']['$oid'];
            $data[$c_id] = $document['entry'][0]['c_name'];
        }

        Helper::showContent(json_encode($data));

    }


    public function generator(): string
    {
        $this->deleteData();

        $qty_containers = (int)$_POST['qty_containers'];
        $capacity_container = (int)$_POST['capacity_container'];
        $unique_products = (int)$_POST['unique_products'];

        // generate products
        $counter_0 = $unique_products;
        $data_products = [];
        while ($counter_0 !== 0) {
            $data_products[] = [
                'id' => $counter_0,
                'name' => 'Product ' . $counter_0,
            ];

            $counter_0--;
        }

        // fill containers
        $counter_1 = $unique_products - 1;
        $data = [];
        while ($qty_containers !== 0) {

            try {
                $capacity = random_int(1, $capacity_container);
            } catch (Exception $e) {
                Helper::showContent(json_encode(['error' => $e->getMessage()]));
            }
            $products = [];

            while ($capacity !== 0) {
                $counter_1 = $counter_1 === -1 ? $unique_products - 1 : $counter_1;
                $products[] = $data_products[$counter_1];
                $capacity--;
                $counter_1--;
            }

            $data[] = [
                'name' => 'Name ' . $qty_containers,
                'products' => $products,
            ];

            $qty_containers--;
        }

        // insert data
        $bulk = new MongoDB\Driver\BulkWrite;
        foreach ($data as $value) {
            $bulk->insert($value);
        }
        try {
            $this->db->executeBulkWrite('lamoda.containers', $bulk);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            Helper::showContent(json_encode(['error' => $e->getMessage()]));
        }

        return 'Data successfully generated!';
    }


    private function deleteData()
    {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete([]);
        try {
            $this->db->executeBulkWrite('lamoda.containers', $bulk);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            Helper::showContent(json_encode(['error' => $e->getMessage()]));
        }
    }


    public function init(): array
    {

        $info = '';
        $cursor = '';

        $query = new MongoDB\Driver\Query([], []);
        try {
            $cursor = $this->db->executeQuery('lamoda.containers', $query);
            $cursor = json_decode(json_encode($cursor->toArray()), true);
        } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
            $info = $e->getMessage();
        }

        $total_containers = 0;
        $unique_products = [];
        $capacity_products = 0;
        $random_id = '';
        foreach ($cursor as $value) {
            $total_containers++;
            $random_id = $value['_id']['$oid'];
            foreach ($value['products'] as $value_1) {
                $unique_products[$value_1['id']] = '';
            }
            if (count($value['products']) > $capacity_products) {
                $capacity_products = count($value['products']);
            }
        }
        $unique_products = count($unique_products);

        return [
            'total_containers' => $total_containers,
            'unique_products' => $unique_products,
            'capacity_products' => $capacity_products,
            'random_id' => $random_id,
            'info' => $info,
        ];
    }

}