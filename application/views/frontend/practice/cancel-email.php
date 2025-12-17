<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <td width="220">Type of Booking	</td>
        <td width="5">:</td>
        <td><?= $p_category; ?></td>
    </tr>
    <tr>
        <td>Slot</td>
        <td>:</td>
        <td><?= $p_label; ?></td>
    </tr>
    <tr>
        <td>Time</td>
        <td>:</td>
        <td><?php echo $schedule; ?></td>
    </tr>        
    <tr>
        <td>Day(s) Left to Practice</td>
        <td>:</td>
        <td><b><?= $c_days_left; ?></b></td>
    </tr>    
</table>