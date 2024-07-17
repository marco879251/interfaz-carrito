<?php
session_start();
include('db.php');

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT carrito.*, productos.nombre, productos.precio, productos.imagen FROM carrito 
        JOIN productos ON carrito.producto_id = productos.id";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if ($result === FALSE) {
    die("Error en la consulta SQL: " . $conn->error);
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="container mt-5">
        <h2>Carrito de Compras</h2>
        <?php if ($result->num_rows > 0): ?>
        <form action="checkout.php" method="post">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): 
                        $subtotal = $row['precio'] * $row['cantidad'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="img-thumbnail" style="width: 50px;">
                            <?php echo $row['nombre']; ?>
                        </td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td>$<?php echo $row['precio']; ?></td>
                        <td>$<?php echo $subtotal; ?></td>
                        <td>
                            <a href="remove_from_cart.php?id=<?php echo $row['producto_id']; ?>" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="text-right">
                <h3>Total: $<?php echo $total; ?></h3>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-credit-card"></i> Pagar
                </button>
            </div>
        </form>
        <?php else: ?>
        <p>No hay productos en el carrito.</p>
        <?php endif; ?>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
