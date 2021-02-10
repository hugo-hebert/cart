<?php
include_once 'ressources.php';

/**
 * Cart class used to store information on products a customer saved
 */
class Cart{
    //List of items in the cart, initialized to an empty array on construction
    private $listOfItems = [];

    /**
     * Adds a certain $quantity of item named $name to the cart
     * 
     * $name is the name of the item to add, string
     * $quantity is the number of items to add, integer
     */
    public function add(string $name, int $quantity): void{
        global $products;

        //get product info
        $product = $this->getProductFromList($name);

        //if the product exists in the list
        if(!empty($product)){

            //if we already have the item in the cart, just add the new ones
            $added = FALSE;
            foreach($this->listOfItems as &$item){
                if($product['name'] === $item['name']){
                    $item['quantity'] += $quantity;
                    $added = TRUE;
                }
            }

            //if we're adding the item for the first time, we have to add all the extra info
            if(!$added){
                $product["quantity"] = $quantity;
                array_push($this->listOfItems, $product);
            }
        }
    }

    /**
     * Removes a certain $quantity of item named $name from the cart
     * $name is the name of the item to remove, string
     * $quantity is the number of items to remove, integer 
     */
    public function remove(string $name, int $quantity): void{

        //Iterate through the whole cart
        foreach($this->listOfItems as $k=>&$item){
            //if we find the right item
            if($name === $item['name']){
                //if the quantity in the cart is LOWER than how many we want to remove
                if($item['quantity'] <= $quantity){
                    //Remove the whole item info array from the cart
                    foreach($item as $key=>$value){
                        unset($item[$key]);
                    }
                    unset($this->listOfItems[$k]);
                }else{
                    //otherwise we just remove the right amount of items from the cart
                    $item['quantity'] -= $quantity;
                }
            }
        }
    }

    /**
     * Calculates totals for each item type in the cart & returns the overall total as double
     */
    private function calculateTotals(): float{
        $tempTotal = 0;
        foreach($this->listOfItems as &$item){
            $item['total'] = $item['price'] * $item['quantity'];
            $tempTotal += $item['total'];
        }

        return $tempTotal;
    }

    /**
     * Returns all product info from $products based on the name given
     * Returns array [name, price]
     */
    private function getProductFromList(string $name): array{
        //get list of products
        global $products;
        //init results
        $result = NULL;
        
        //search through the list
        foreach($products as $product){
            //if we find a match
            if($product['name'] === $name){
                //return product array [name, price]
                $result = $product;
            }
        }
        return $result;
    }

    /**
     * HTML display of the cart
     */
    public function display(): void{
        //get the totals
        $overalTotal = $this->calculateTotals();
        ?>
        <div id="cart">
            <h3>Current Cart:</h3>
            <table>
                <tr>
                    <th>Product name</th>
                    <th>Price per unit</th>
                    <th>Quantity in cart</th>
                    <th>Product total cost</th>
                    <th>Remove from Cart</th>
                    <th>Overall total</th>
                </tr>
        <?php
        //for each item inside the cart
        foreach($this->listOfItems as $item){
            ?>
                <tr>
                    <form action="modifyCart.php" method="post">
                    <input type="hidden" name="name" value="<?php echo $item['name']; ?>" readonly>
                    <input type="hidden" name="method" value="remove" readonly>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo displayPrice($item['price']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo displayPrice($item['total']);?></td>
                    <td>
                        <input type="number" value="1" name="quantity" min="1">
                        <input type="submit" value="Remove from cart">
                    </td>
                    </form>
                </tr>   
            <?php
        }

        ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $overalTotal ?></td>
                </tr>
            </table>
        </div>
        <?php
    }
}
?>