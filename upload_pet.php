<!DOCTYPE html>
<html>
<body>

<h2>Upload Pet Image</h2>

<form action="match_api.php" method="post" enctype="multipart/form-data">
    <input type="file" name="pet_image" required>
    <br><br>
    <button type="submit">Find Pet</button>
</form>

</body>
</html>
