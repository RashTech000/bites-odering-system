function toggleDelivery(){
  const method=document.getElementById('delivery_method');
  const box=document.getElementById('deliveryBox');
  if(method && box) box.classList.toggle('hidden', method.value!=='delivery');
}
function togglePayment(){
  const method=document.getElementById('payment_method');
  const box=document.getElementById('paymentBox');
  if(method && box) box.classList.toggle('hidden', method.value!=='mobile');
}
document.addEventListener('DOMContentLoaded', function(){
  toggleDelivery();
  togglePayment();
});
