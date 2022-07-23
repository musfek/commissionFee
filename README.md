## **Commission Fee Calculation**

####**INSTALL THE APP**


Clone the project

      git clone git@github.com:sharetripnet/musfek/commissionFee.git
      
Run composer

    composer install 
    
    
    
####**RUN THE APP**
    
PHP COMMAND FOR RUN THE APPLICATION

    php yii serve
    
BROWSER URL TO GET OUTPUT

    http://localhost:8080/?fileName=input.csv
    
PHP COMMAND FOR RUN THE UNIT TEST

    php vendor/bin/codecept run  unit
    
    
 
####**APP'S SHORT DESCRIPTION**
Main controller is provided below, where all requests are processed.

       controllers/CommissionFeeController.php
       
 This controller passes all requests to the model provided below, where all operations are calculated.
  
        models/CommissionFee/CommissionFee.php
        
 This CommissionFee model has `calculate` function to calculate all operations by calling `operation` function which processes only one operation at a time. 
 
 Every operation has two types `deposit` and `withdraw`. Every type of operation is processed in the individual class provided below.
      
        models/CommissionFee/Deposit.php
        models/CommissionFee/Withdraw.php
          