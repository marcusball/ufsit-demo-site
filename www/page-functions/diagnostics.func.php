<?php
class DiagnosticPage extends PageObject{
	public function pageTitle(){
		echo "Diagnostic Information"; 
	}
	
	public function printDiagnostics(){
		$this->printDbInfo();
	}
	
	public function printDbInfo(){
		if(HAS_DATABASE){
			echo "Has Database";
		}
		else{
			echo "No Database";
		}
	}
}