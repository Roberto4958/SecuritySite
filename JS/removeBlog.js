$(document).ready(function(){
    // DOM ready
 $(".delete").click(function() {
     makeAPIRequest($(this).data("id"), $(this).data("userid"), $(this));
    });
    
});


function makeAPIRequest(id, userID, row){
    $.ajax({
                url: "removeBlog.php",
                type: 'POST',
                data: { id: id, userid: userID },
                crossDomain: true,
                dataType: 'json',
                beforeSend: function(){ //make a spiner
                    
                    },
                success: function (data) {
                    if(data){
                        if(data.success){
                            //$( ".hello" ).remove();
                            row.closest('.row').remove();
                            alert("blog has been deleted successfully");
                            
                        }
                        else alert('you are not authorized to delete this blog');
                    }
                    else alert("Server Error");
                    
                    },
                error: function (err) { 
                    alert("Server Error");
                }
            });

}


  
