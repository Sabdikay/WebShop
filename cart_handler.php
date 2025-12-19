<?php
session_start();

$users = json_decode(file_get_contents("users.json"), true);

foreach ($users as $user) {
    if ($user['userId'] == $_SESSION['userId'] && $user['isBlocked']) {
        die("Your account is blocked. You cannot place orders.");
    }
}

header('Content-Type: application/json');

// Get cart session ID
if (!isset($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = 'cart_' . session_id();
}
$cartId = $_SESSION['cart_id'];

// Create carts directory if it doesn't exist
$cartsDir = __DIR__ . "/carts";
if (!is_dir($cartsDir)) {
    mkdir($cartsDir, 0777, true);
}

$cartFile = $cartsDir . "/" . $cartId . ".json";

// Load products data
$productsFile = __DIR__ . "/products.json";
if (!file_exists($productsFile)) {
    echo json_encode(['success' => false, 'message' => 'Products file not found']);
    exit;
}

$productsData = json_decode(file_get_contents($productsFile), true);
$products = $productsData['product'];

// Function to load cart
function loadCart($cartFile, $cartId) {
    if (file_exists($cartFile)) {
        return json_decode(file_get_contents($cartFile), true);
    }
    
    return [
        'cart_id' => $cartId,
        'created' => date('Y-m-d\TH:i:s'),
        'last_updated' => date('Y-m-d\TH:i:s'),
        'items' => [],
        'totals' => [
            'subtotal' => 0,
            'tax' => 0,
            'discount' => 0,
            'total' => 0
        ]
    ];
}

// Function to save cart
function saveCart($cartFile, $cartData) {
    $cartData['last_updated'] = date('Y-m-d\TH:i:s');
    file_put_contents($cartFile, json_encode($cartData, JSON_PRETTY_PRINT));
}

// Function to calculate totals
function calculateTotals(&$cartData) {
    $subtotal = 0;
    
    foreach ($cartData['items'] as &$item) {
        $item['subtotal'] = $item['price'] * $item['quantity'];
        $subtotal += $item['subtotal'];
    }
    
    $tax = $subtotal * 0.19; // 19% tax
    $total = $subtotal + $tax - $cartData['totals']['discount'];
    
    $cartData['totals']['subtotal'] = round($subtotal, 2);
    $cartData['totals']['tax'] = round($tax, 2);
    $cartData['totals']['total'] = round($total, 2);
}

// Function to find product by pid
function findProduct($products, $pid) {
    foreach ($products as $product) {
        if ((string)$product['pid'] === (string)$pid) {
            return $product;
        }
    }
    return null;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $pid = $_POST['pid'] ?? '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    $cartData = loadCart($cartFile, $cartId);
    
    switch ($action) {
        case 'add':
            $product = findProduct($products, $pid);
            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
            
            // Check if item already exists in cart
            $itemExists = false;
            foreach ($cartData['items'] as &$item) {
                if ((string)$item['pid'] === (string)$pid) {
                    $item['quantity'] += $quantity;
                    $itemExists = true;
                    break;
                }
            }
            
            // Add new item if it doesn't exist
            if (!$itemExists) {
                $cartData['items'][] = [
                    'pid' => $product['pid'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'subtotal' => $product['price'] * $quantity,
                    'imagepath' => $product['imagepath']
                ];
            }
            
            calculateTotals($cartData);
            saveCart($cartFile, $cartData);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Item added to cart',
                'itemCount' => count($cartData['items'])
            ]);
            break;
            
        case 'update':
            $updated = false;
            foreach ($cartData['items'] as &$item) {
                if ((string)$item['pid'] === (string)$pid) {
                    $item['quantity'] = max(1, $quantity);
                    $updated = true;
                    break;
                }
            }
            
            if ($updated) {
                calculateTotals($cartData);
                saveCart($cartFile, $cartData);
                echo json_encode(['success' => true, 'message' => 'Cart updated']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
            }
            break;
            
        case 'remove':
            $originalCount = count($cartData['items']);
            $cartData['items'] = array_filter($cartData['items'], function($item) use ($pid) {
                return (string)$item['pid'] !== (string)$pid;
            });
            $cartData['items'] = array_values($cartData['items']); // Reindex array
            
            if (count($cartData['items']) < $originalCount) {
                calculateTotals($cartData);
                saveCart($cartFile, $cartData);
                echo json_encode(['success' => true, 'message' => 'Item removed']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Item not found']);
            }
            break;
            
        case 'clear':
            $cartData['items'] = [];
            calculateTotals($cartData);
            saveCart($cartFile, $cartData);
            echo json_encode(['success' => true, 'message' => 'Cart cleared']);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit;
}

// Handle GET requests (get cart data)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cartData = loadCart($cartFile, $cartId);
    echo json_encode(['success' => true, 'cart' => $cartData]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method']);
?>