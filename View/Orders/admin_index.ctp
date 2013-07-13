<h1>Order Admin</h1>
<table>
    <?php foreach($orders as $order): ?>
        <tr><td><?php echo $order['Order']['id']; ?></td></tr>
    <?php endforeach; ?>
</table>