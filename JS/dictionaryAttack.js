$(document).ready(function(){
    // DOM ready
 $("#findhash").click(function() {
     //alert("hello world" + $('#userInput').val());
     makeAPIRequest();
    });
    
});


function makeAPIRequest(){
    alert('in method');
    $.ajax({
                url: "easypasswords",
                type: 'POST',
                data: { algorithm: "sha"},
                crossDomain: true,
                dataType: 'json',
                beforeSend: function(){ 
                    
                    },
                success: function (data) {
                    if(data){
                        if(data.success){
        
                            alert("data retrieved success");
                            
                        }
                        else alert('fist data: '+ data['5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5']);
                    }
                    else alert("Server Error");
                    
                    },
                error: function (err) { 
                    alert("Server Error");
                }
            });

}


  
