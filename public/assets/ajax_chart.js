    
$(document).ready(function () {
       
        // the chart config 
        let myElement = $('#myChart');
        let myChartGraphic = new Chart( myElement , {
            type: 'pie',
            data: { 
                labels: [ "timeSlots" , "appointment" ],
                datasets: [{ 
                    label: "appointment/slots" ,
                    data: [  ],
                    backgroundColor: ["red", "green"]
                }]
            }
        }   
        );

        
        //first update with first json request
        $.get('/piedata' ,function(response){  
            console.log(response);
            // Do something with the response data here
            
            var newData = [response.totalAvailableTimeSlots, response.totalAppointment]
            console.log(newData);
            
            myChartGraphic.data.datasets[0].data = newData;
            myChartGraphic.data.datasets[0].label = response.labelmsg;
            myChartGraphic.update();
         })
        

        //the ajax using time interva-function "setInterval" 
        setInterval(function() {
        $.ajax({
            url: "/piedata",
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log(response);
                // Do something with the response data here
                

                
                var newData = [response.totalAvailableTimeSlots , response.totalAppointment]
                console.log(newData);
                
                myChartGraphic.data.datasets[0].data = newData;
                // myChartGraphic.data.datasets[0].label = response.labelmsg;
                myChartGraphic.update();
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }, 3000); // 10 seconds in milliseconds

        
    

});
