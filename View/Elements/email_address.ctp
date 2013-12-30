<tr>
    <td colspan=4 style="padding: 0px 10px;">
        <p style="font-size: 24px; color: #665600"><?php echo ucfirst($address['type']); ?> Address</p>
    </td>
</tr>
<tr style="padding: 5px">
    <td colspan=2><strong>Name</strong></td>
    <td colspan=2><?php echo $address['name']; ?></td>
</tr>
<?php if(!empty($address['company'])): ?>
<tr style="padding: 5px">
    <td colspan=2><strong>Company</strong></td>
    <td colspan=2><?php echo $address['company']; ?></td>
</tr>
<?php endif; ?>
<tr style="padding: 5px">
    <td colspan=2><strong>Address</strong></td>
    <td colspan=2><?php echo $address['address1']; ?></td>
</tr>
<?php if(!empty($address['address2'])): ?>
    <tr>
        <td colspan=2></td>
        <td colspan=2><?php echo $address['address2']; ?></td>
    </tr>
<?php endif; ?>
<tr style="padding: 5px">
    <td colspan=2><strong>City</strong></td>
    <td colspan=2><?php echo $address['city']; ?></td>
</tr>
<?php if(!empty($address['state'])): ?>
    <tr style="padding: 5px">
        <td colspan=2><strong>State or Province</strong></td>
        <td colspan=2><?php echo $address['state']; ?></td>
    </tr>
<?php endif; ?>
<tr style="padding: 5px">
    <td colspan=2><strong>Postal Code</strong></td>
    <td colspan=2><?php echo $address['postalCode']; ?></td>
</tr>
<tr style="padding: 5px">
    <td colspan=2><strong>Country</strong></td>
    <td colspan=2><?php echo $address['countryName']; ?></td>
</tr>