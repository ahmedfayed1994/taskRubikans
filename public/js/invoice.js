 $(document).ready(function(){
	$(document).on('click', '#checkAll', function() {
		$(".itemRow").prop("checked", this.checked);
	});
	$(document).on('click', '.itemRow', function() {
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});
	var count = $(".itemRow").length;
     $(document).on('click', '#addRows', function() {
		count++;
         var htmlRows = '';
		htmlRows += '<tr>';
		htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
        htmlRows += '<td><select class="form-control" name="product_id[]" id="productName_'+count+'"></select></td>';
        htmlRows += '<td><select class="form-control" name="unit[]" id="unit_'+count+'"></select></td>';
        htmlRows += '<td><input type="number" name="quantity[]" id="quantity_'+count+'" class="form-control quantity" autocomplete="off"></td>';
        htmlRows += '<td><input type="number" name="price[]" id="price_'+count+'" class="form-control price" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="total[]" id="total_'+count+'" class="form-control total" autocomplete="off"></td>';
		htmlRows += '</tr>';
		$('#invoiceItem').append(htmlRows);

         var units = $("#unit_"+count);
         $("#unit_1 option").each(function()
         {
             $val =  $(this).val();
             $text = $(this).text();

             var option = $("<option />");
             option.html($text);
             option.val($val);
             units.append(option);

         });

         var productNames = $("#productName_"+count);
         $("#productName_1 option").each(function()
         {


             $val =  $(this).val();
             $text = $(this).text();

             var option = $("<option />");
             option.html($text);
             option.val($val);
             productNames.append(option);

         });


         if (count <= 2){
             var index = $("#productName_1").get(0).selectedIndex;
         }else {
             var index = $("#productName_"+count).get(0).selectedIndex;
         }

         $('#productName_'+count+' option:eq(' + index + ')').remove();

     });
	$(document).on('click', '#removeRows', function(){
		$(".itemRow:checked").each(function() {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
		calculateTotal();
	});
	$(document).on('blur', "[id^=quantity_]", function(){
        checkquantity();
		calculateTotal();
	});
	$(document).on('blur', "[id^=price_]", function(){
		calculateTotal();
	});
	$(document).on('blur', "#taxRate", function(){
		calculateTotal();
	});
	$(document).on('blur', "#amountPaid", function(){
		var amountPaid = $(this).val();
		var totalAftertax = $('#totalAftertax').val();
		if(amountPaid && totalAftertax) {
			totalAftertax = totalAftertax-amountPaid;
			$('#amountDue').val(totalAftertax);
		} else {
			$('#amountDue').val(totalAftertax);
		}
	});
	$(document).on('click', '.deleteInvoice', function(){
		var id = $(this).attr("id");
		if(confirm("Are you sure you want to remove this?")){
			$.ajax({
				url:"action.php",
				method:"POST",
				dataType: "json",
				data:{id:id, action:'delete_invoice'},
				success:function(response) {
					if(response.status == 1) {
						$('#'+id).closest("tr").remove();
					}
				}
			});
		} else {
			return false;
		}
	});
});
function calculateTotal(){
	var totalAmount = 0;
	$("[id^='price_']").each(function() {
		var id = $(this).attr('id');
		id = id.replace("price_",'');
		var price = $('#price_'+id).val();
		var quantity  = $('#quantity_'+id).val();
		if(!quantity) {
			quantity = 1;
		}
		var total = price*quantity;
		$('#total_'+id).val(parseFloat(total));
		totalAmount += total;
	});
	$('#subTotal').val(parseFloat(totalAmount));
	var taxRate = $("#taxRate").val();
	var subTotal = $('#subTotal').val();
	if(subTotal) {
		var taxAmount = subTotal*taxRate/100;
		$('#taxAmount').val(taxAmount);
		subTotal = parseFloat(subTotal)+parseFloat(taxAmount);
		$('#totalAftertax').val(subTotal);
		var amountPaid = $('#amountPaid').val();
		var totalAftertax = $('#totalAftertax').val();
		if(amountPaid && totalAftertax) {
			totalAftertax = totalAftertax-amountPaid;
			$('#amountDue').val(totalAftertax);
		} else {
			$('#amountDue').val(subTotal);
		}
	}




}

function checkquantity(){
    $("[id^='quantity_']").each(function() {
        var id = $(this).attr('id');
        id = id.replace("quantity_",'');
        if(id)
        var timer = null;
        $("#quantity_"+id).keyup(function(){
            clearTimeout(timer);
            timer = setTimeout(function (){
                var quantity  = $('#quantity_'+id).val();
                var product = $('#productName_'+id).val();
                $.ajax
                ({
                    type: "GET",
                    url: "check/quantity",
                    data: {product_id:product, quantity:quantity},
                    cache: false,
                    success: function (data) {
                        $('.error_msg').html(data);
                    }
                });
            }, 1000)
        });
    });


}

 $(document).ready(function () {
     $("#store_id").change(function () {
         var idData = $(this).val();
         var dataString = 'id=' + idData;

         $.ajax
         ({
             type: "GET",
             url: "get/item",
             data: dataString,
             cache: false,
             success: function (data) {
                 $('#productName_1').html(data);
             }
         });

     });
 });

