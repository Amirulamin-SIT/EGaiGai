<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once "./src/functions/sql/sql.php";
require_once "./src/functions/sql/order_sql.php";


final class CheckoutSQLTest extends TestCase
{   
    public $orders;

    protected function setUp(): void
    {
        $conn = get_conn();
        $this->orders = $conn->query("SELECT * FROM orders");
    }

    //======== TEST CASES =========
    function test_create_order()
    {
        create_order(7);
        //check if new order is created
        $conn = get_conn();
        $innerOrders = $conn->query("SELECT * FROM orders");
        $this->assertEquals(mysqli_num_rows($this->orders) + 1, mysqli_num_rows($innerOrders));
    }
}
?>