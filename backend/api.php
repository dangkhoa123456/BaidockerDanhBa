<?php
// 1. Lấy thông tin từ cấu hình Render
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

// 2. Kiểm tra: Nếu không có biến môi trường (tức là đang chạy ở máy local), thì dùng localhost
if (!$host) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'test'; // Tên DB trên máy tính của bạn
    $port = 3306;
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// ===== CORS HANDLING =====
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ===== ROUTING =====
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? 'get';

switch ($method) {
    case 'GET':
        if ($action === 'get') {
            handleGet($pdo);
        }
        break;
    
    case 'POST':
        handlePost($pdo);
        break;
    
    case 'PUT':
        handlePut($pdo);
        break;
    
    case 'DELETE':
        handleDelete($pdo);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

// ===== CRUD FUNCTIONS =====

// GET: Lấy danh sách (search nếu có ?q=...)
function handleGet($pdo) {
    $search = $_GET['q'] ?? '';
    
    if ($search) {
        $sql = "SELECT * FROM contacts WHERE name LIKE :search ORDER BY name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['search' => "%$search%"]);
    } else {
        $sql = "SELECT * FROM contacts ORDER BY name";
        $stmt = $pdo->query($sql);
    }
    
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($contacts);
}

// POST: Thêm mới
function handlePost($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['name']) || !isset($data['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and phone required']);
        return;
    }
    
    $sql = "INSERT INTO contacts (name, phone) VALUES (:name, :phone)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':name' => $data['name'],
            ':phone' => $data['phone']
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add contact']);
    }
}

// PUT: Cập nhật
function handlePut($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID, name and phone required']);
        return;
    }
    
    $sql = "UPDATE contacts SET name = :name, phone = :phone WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':phone' => $data['phone']
        ]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update contact']);
    }
}

// DELETE: Xóa theo ID
function handleDelete($pdo) {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID required']);
        return;
    }
    
    $sql = "DELETE FROM contacts WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete contact']);
    }
}
?>
