<?php
session_start();
include "connection.php";

$creator_id = $_SESSION['Creator_ID'] ?? null;
if (!$creator_id) {
    header("Location: Csignin.php");
    exit;
}

// Handle state updates (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $action = $_POST['action'] ?? null;

    $allowedStates = ['accepted', 'rejected', 'dispatched'];
    if ($order_id && in_array($action, $allowedStates)) {
        $update = $conn->prepare("UPDATE `Order` SET state = ? WHERE Order_ID = ?");
        $update->bind_param("si", $action, $order_id);
        $update->execute();
        $update->close();
    }
    header("Location: recipeRequest.php");
    exit;
}

// Fetch creator-related orders
$sql = "
    SELECT o.Order_ID, o.Quantity, o.state, o.Address,
           r.Recipe_title, r.Price,
           v.Name AS customer_name
    FROM `Order` o
    JOIN Recipe r ON o.Recipe_ID = r.Recipe_ID
    JOIN viewerRegister v ON o.Viewer_ID = v.Viewer_ID
    WHERE r.Creator_ID = ?
    ORDER BY o.Order_ID DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $creator_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Requests</title>
    <link rel="stylesheet" href="../YCD/css/recipeRequest.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Order Requests</h1>
            <div class="logo">
                <img src="../YCD/images/logo.png" alt="Logo">
            </div>
        </header>
        <hr width="100%" size="1" color="gray">
        <br><br>
        <div class="filter-section">
            <label>Filter by Status:</label>
            <div class="dropdown">
                <button class="dropdown-btn">ALL ▼</button>
                <div class="dropdown-content">
                    <a href="#" data-status="ALL">ALL</a>
                    <a href="#" data-status="PENDING">PENDING</a>
                    <a href="#" data-status="PREPARING">PREPARING</a>
                    <a href="#" data-status="DISPATCHED">DISPATCHED</a>
                    
                </div>
            </div>
        </div>
        <table id="orders-table">
            <thead>
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Address</th>
        <th>Recipe</th>
        <th>Quantity</th>
        <th>Price(LKR)</th>
        <th>Status</th>
        <th>Actions</th>
        <th>Connect Rider</th> 
    </tr>
</thead>
           <tbody>
<?php foreach ($orders as $order): ?>
<tr>
    <td><?= $order['Order_ID'] ?></td>
    <td><?= htmlspecialchars($order['customer_name']) ?></td>
    <td><?= htmlspecialchars($order['Address']) ?></td>
    <td><?= htmlspecialchars($order['Recipe_title']) ?></td>
    <td><?= $order['Quantity'] ?></td>
    <td><?= number_format($order['Quantity'] * $order['Price'], 2) ?></td>
    <td>
        <span class="status <?= strtolower($order['state']) ?>">
            <?= strtoupper($order['state']) ?>
        </span>
    </td>
    <td>
        <?php if ($order['state'] === NULL): ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $order['Order_ID'] ?>">
                <input type="hidden" name="action" value="accepted">
                <button class="action-btn accept">Accept</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $order['Order_ID'] ?>">
                <input type="hidden" name="action" value="rejected">
                <button class="action-btn reject">Reject</button>
            </form>
        <?php elseif ($order['state'] === 'accepted'): ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $order['Order_ID'] ?>">
                <input type="hidden" name="action" value="dispatched">
                <button class="action-btn dispatched">Dispatched</button>
            </form>
        <?php elseif ($order['state'] === 'dispatched'): ?>
            <span>Completed</span>
        <?php else: ?>
            <span>-</span>
        <?php endif; ?>
    </td>
    <td>
        <form method="GET" action="findRider.php" style="display:inline;">
            <input type="hidden" name="order_id" value="<?= $order['Order_ID'] ?>">
            <button type="submit" class="action-btn connect">Connect</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
        </table>
    </div>

    <!-- Popup for Reject confirmation -->
    <div id="reject-popup" class="popup" style="display: none;">
        <div class="popup-content">
            <p>Are you sure reject this order?</p>
            <div class="popup-buttons">
                <button id="reject-yes" class="action-btn reject">Yes</button>
                <button id="reject-no" class="action-btn">No</button>
            </div>
        </div>
    </div>

    <script>
    // Dropdown functionality
    const dropdownBtn = document.querySelector('.dropdown-btn');
    const dropdownContent = document.querySelector('.dropdown-content');
    const dropdownItems = document.querySelectorAll('.dropdown-content a');
    const ordersTable = document.getElementById('orders-table');

    dropdownBtn.addEventListener('click', () => {
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });

    dropdownItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const selectedStatus = item.getAttribute('data-status').toUpperCase();
            dropdownBtn.textContent = item.textContent + ' ▼';
            dropdownContent.style.display = 'none';
            filterRowsByStatus(selectedStatus);
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
            dropdownContent.style.display = 'none';
        }
    });

    function filterRowsByStatus(status) {
        const rows = ordersTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const statusText = row.querySelector('.status')?.textContent?.toUpperCase();
            if (status === 'ALL' || statusText === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    

    // Handle Reject confirmation popup
    const rejectPopup = document.getElementById('reject-popup');
    const rejectYesBtn = document.getElementById('reject-yes');
    const rejectNoBtn = document.getElementById('reject-no');   


 
  document.querySelectorAll('form .reject').forEach(button => {
    button.addEventListener('click', function (e) {
      if (!confirm('Are you sure you want to reject this order?')) {
        e.preventDefault(); // prevent form submission if user clicks "Cancel"
      }
    });
  });

   
</script>

</body>
</html>