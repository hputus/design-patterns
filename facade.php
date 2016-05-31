<?php
//facade pattern is used to provide a nice clean interface for complex or multiple subsystems.
//differs from the Adapter because it creates a brand new interface, whereas Adapter makes things
//implement an existing interface.
class CompanyFacade{
	public function __construct(){
		$this->billing = new BillingDepartment();
		$this->service = new CustomerServiceDepartment();
		$this->other = new SomeOtherDepartment();
	}
	
	public function bill(){
		echo "\nbill method called in facade\n";
		$bill = $this->billing->createBill();
		$this->billing->sendBill($bill);
		$this->service->phoneCustomer("did you receive the bill?");
		$this->billing->latePaymentCharge();
	}
	
	public function complain(){
		echo "\ncomplain method called in facade\n";
		$this->service->phoneCustomer("do you have any complaints?");
		$this->service->logCustomerComplaint();
	}
}

class BillingDepartment{
	public function createBill(){
		echo "creating new bill\n";
	}
	public function sendBill($bill){
		echo "sending bill in the post\n";
	}
	public function cancelBill(){
		echo "canceling bill\n";
	}
	public function latePaymentCharge(){
		echo "charging customer for late payment\n";
	}
}
class CustomerServiceDepartment{
	public function logCustomerComplaint(){
		echo "logging customer complaint\n";
	}
	public function phoneCustomer($message){
		echo "phoning customer to say: $message\n";
	}
}
class SomeOtherDepartment{
	public function doSomething(){
		echo "doing some kind of thing\n";
	}
}

echo "<pre>";
//we can now call a set of very simple methods rather than needing to know the internal
//workings of things
$company = new CompanyFacade();
$company->bill();
$company->complain();