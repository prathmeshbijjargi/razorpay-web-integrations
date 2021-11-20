<?php 
include('templates/header.php');
?>
<form name="razorpay_frm_payment" class="razorpay-frm-payment" id="razorpay-frm-payment" method="post">
<input type="hidden" name="language" value="EN">
<section class="showcase">
  <div class="container">
    <div class="pb-2 mt-4 mb-2 border-bottom">
      <h2>Razorpay Payment Gateway</h2>
    </div>

    <div class="row align-items-center">
       <div class="form-group col-md-6">
        <label for="inputEmail4">Amount</label>
        <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" required>
      </div>

      <div class="form-group col-md-6">
        <label for="inputEmail4">Full Name</label>
        <input type="text" name="billing_name" class="form-control" id="billing-name"  Placeholder="Name" required> 
      </div>
  </div>

    <div class="row align-items-center">
       <div class="form-group col-md-6">
        <label for="inputEmail4">Email</label>
        <input type="email" name="billing_email"class="form-control" id="billing-email" Placeholder="Email" required>
      </div>

      <div class="form-group col-md-6">
        <label for="inputEmail4">Api Key</label>
        <input type="text" name="api_key" class="form-control" id="api_key" Placeholder="Api Key" required>
      </div>
  </div>
      <div class="row align-items-center">
      <div class="form-group col-md-6">
        <label for="inputEmail4">Secret Key</label>
         <input type="text" name="secret_key" class="form-control" id="secret_key"  Placeholder="Secret Key" required>
      </div>
    <div class="row">
      <div class="col">
        <button type="button" class="btn btn-success mt-4 float-right" id="razor-pay-now"><i class="fa fa-credit-card" aria-hidden="true"></i> Pay</button>
      </div>
    </div>
</div>
</section>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">

    jQuery(document).on('click', '#razor-pay-now', function (e) {
      var total = jQuery('input#amount').val() * 100;
      var name = jQuery('input#billing-name').val();
      var currency_code_id = jQuery('input#currency').val();
      var key_id =  jQuery('input#api_key').val();
      var secret_key =  jQuery('input#secret_key').val();
      var store_name = 'Razorpay';
      var store_description = 'Payment';
      var store_logo = 'https://d6xcmfyh68wv8.cloudfront.net/assets/razorpay-glyph.svg';
      var email = jQuery('input#billing-email').val();
      jQuery('.text-danger').remove();

    if(name=="") {
      jQuery('input#billing-name').after('<small class="text-danger">Please enter name.</small>');
      return false;
    }
    if(email=="") {
      jQuery('input#billing-email').after('<small class="text-danger">Please enter valid email.</small>');
      return false;
    }
    if(total=="") {
        jQuery('input#billing-name').after('<small class="text-danger">Please enter valid amount.</small>');
        return false;
    }
    if(key_id=="") {
        jQuery('input#billing-email').after('<small class="text-danger">Please enter valid key.</small>');
        return false;
    }
        $.ajax({
            type: "POST",
            url: 'order.php',
            data: {'key':key_id,'secret':secret_key,'amount':total,'email':email,'name':name},
            success: function(response)
            {
                var result=JSON.parse(response)
                console.log(result);
                if(result.error){
                    alert('Please contact integration team')
                    document.getElementById("razorpay-frm-payment").reset()
                }else {
                    open_checkout(result)
                }
            }
        });
        function open_checkout(x){
            var razorpay_options = {
                key: key_id,
                amount: total,
                order_id:x.order_id,
                name: store_name,
                description: store_description,
                image: store_logo,
                currency: currency_code_id,
                prefill: {
                    name: name,
                    email: email
                },
                notes: {
                    order_id: x.order_id
                },
                "handler": function (response){
                    alert(response.razorpay_payment_id);
                    alert(response.razorpay_order_id);
                    alert(response.razorpay_signature);
                    document.getElementById("razorpay-frm-payment").reset()
                }
            };
            // obj
            var objrzpv1 = new Razorpay(razorpay_options);
            objrzpv1.on('payment.failed', function (response){
                alert(response.error.code);
                alert(response.error.description);
                alert(response.error.source);
                alert(response.error.step);
                alert(response.error.reason);
                alert(response.error.metadata.order_id);
                alert(response.error.metadata.payment_id);
            });
            objrzpv1.open();
            e.preventDefault();
        }

});
</script>