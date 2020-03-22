<?php  
    session_start();
 ?>
<center><h1>Do anything with session.</h1></center><br>
<form method="POST" action="data.php" name="checkout">
    <input name="billing_image" placeholder="Link of Uploaded Image" class="calResult"><br>
    <input type="submit" value="Submit" class="buttonCount">             
</form>

<script>
    // Get value from button
    // checkout (Form name of checkout page)
    // billing_image (input name of form)
    document.checkout.billing_image.value = "<?php echo $_SESSION['billing_image']; ?>";
</script>