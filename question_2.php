<?php
namespace SoftwareEngineerTest;

// Question 2 & 3 & 4

/**
 * Class Customer
 */
abstract class Customer {

	/**
	 * Define the max length of customer username string
	 */
	const MAX_USERNAME_LENGTH = 30;

	/**
	 * Customer Id
	 * @var
	 */
	protected $id;

	/**
	 * Customer Username
	 * @var
	 */
	protected $username;

	/**
	 * Customer account balance
	 * @var int
	 */
	protected $balance = 0;

	/**
	 * Credit deposit bonus by client type
	 * @var
	 */
	protected $deposit_bonus;

	/**
	 * Customer type: 'B' for Bronze, 'S' for Silver, 'G' for Gold
	 * @var
	 */
	protected $type;


	public function __construct($id) {
		$this->id = $id;
	}

	/**
	 * Get a customer's current credit balance;
	 * @return int
	 */
	public function get_balance() {
		return $this->balance;
	}

	/**
	 * Generate a username string for base on client type;
	 *
	 * @param null $length
	 *
	 * @return string
	 */
	public function generate_username($length = null) {
		$this->username =  $this->type . $this->generate_random_string($length);

		return $this->username;
	}


	/**
	 * Generate a random string to customer's username;
	 *
	 * @param null $length string length
	 *
	 * @return string random string
	 */
	protected function generate_random_string($length = null) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$length = $length && strlen($length) <= static::MAX_USERNAME_LENGTH ? $length : static::MAX_USERNAME_LENGTH;
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[mt_rand(0, $charactersLength - 1)];
		}

		return $randomString;
	}

	/**
	 * Customer Deposit credit
	 *
	 * @param $deposit
	 */
	public function deposit($deposit) {
		$this->balance += $deposit * (1 + $this->deposit_bonus);
	}
}

#Question 2 ====================================================================
/**
 *
 * Class Bronze_Customer
 *
 * @package SoftwareEngineerTest
 */
class Bronze_Customer extends Customer {

	protected $deposit_bonus = 0;
	protected $type = 'B';

}

/**
 * Class Silver_Customer
 *
 * @package SoftwareEngineerTest
 */
class Silver_Customer extends Customer {

	protected $deposit_bonus = .05;
	protected $type = 'S';

}

/**
 * Class Gold_Customer
 *
 * @package SoftwareEngineerTest
 */
class Gold_Customer extends Customer {

	protected $deposit_bonus = .1;
	protected $type = 'G';

}

#Question 3 ====================================================================
/**
 * Customer Factory to create a customer by customer id
 *
 * Class CustomerFactory
 *
 * @package SoftwareEngineerTest
 */
class CustomerFactory {

	/**
	 * All customer subclass's class suffix
	 *
	 * @var string
	 */
	static $suffix = '_Customer';

	/**
	 * Instantiate the correct object (Gold, Silver or Bronze customer) given a customer ID .
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public static function get_instance($id) {
		$customer_type  = static::validate_customer_id($id);
		$customer_class = static::get_class_from_type($customer_type);

		return new $customer_class($id);
	}

	/**
	 * Validate a customer's id passed in
	 *
	 * rule: numbers only, no more than 10 characters in total, first character should represent a valid customer class
	 * eg 'B' for Bronze, 'S' for Silver, 'G' for Gold
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	protected static function validate_customer_id($id) {
		if (!preg_match('/^([A-Z]{1})\d{0,9}$/', $id, $matches)) {

			throw new \InvalidArgumentException('Invalid Customer ID');
		}

		return $matches[1];
	}

	/**
	 * Check if the customer class of request type has been defined
	 *
	 * @param $type
	 *
	 * @return mixed
	 */
	protected static function get_class_from_type($type) {
		$patten = __NAMESPACE__ . '\\\\' . strtoupper($type) . '.*' . static::$suffix;
		$declared_class = get_declared_classes();
		$customer_class = array_filter($declared_class, function ($class) use ($patten) {
			return preg_match("/$patten/", $class);
		});

		if (count($customer_class) != 1 || ! class_exists(current($customer_class))) {

			throw new \InvalidArgumentException('Invalid Customer ID');
		}

		return current($customer_class);
	}

}

echo '<pre>';
try {
	$bronze = CustomerFactory::get_instance('B12314');
	$bronze->generate_username(40);
	var_dump($bronze);
	$bronze->deposit(200);
	$bronze->deposit(200);
	echo 'Bronze Customer balance:' . $bronze->get_balance() . '<br><br>';

	$silver = CustomerFactory::get_instance('S23231241');
	$silver->generate_username(5);
	var_dump($silver);
	$silver->deposit(200);
	$silver->deposit(200);
	echo 'Silver Customer balance:' . $silver->get_balance() . '<br><br>';

	$gold = CustomerFactory::get_instance('G2233');
	$gold->generate_username(20);
	var_dump($gold);
	$gold->deposit(200);
	$gold->deposit(200);
	echo 'Gold Customer balance:' . $gold->get_balance() . '<br><br>';
} catch (\Exception $e) {
	echo $e->getMessage();
}
echo '</pre>';


