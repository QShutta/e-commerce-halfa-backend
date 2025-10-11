<?php
/*On the favorites page:
The user removes a product from favorites by specifying the favorite_id.
On the favorites page, you typically list all favorite items,
each with its unique favorite_id,
so you can directly use it to delete the entry.
*/
include "../connect.php";

$favorite_id = filterRequest('favorite_id');

deleteData("favorite","favorite_id =$favorite_id");
?>