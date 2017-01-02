//$.pjax.reload({container: '#pjax-clock', timeout: 1000});

$(document).ready(
        function() {
            setInterval(function() {
                var date = new Date();
                var d = date.getDate();
                if (d < 10){
                    d = '0' + d;
                }
                var m = date.getMonth()+1;
                if (m < 10){
                    m = '0' + m;
                }
                var y = date.getFullYear();
                var H = date.getHours();
                if (H < 10){
                    H = '0' + H;
                }
                var i = date.getMinutes();
                if (i < 10){
                    i = '0' + i;
                }
                var s = date.getSeconds();
                if (s < 10){
                    s = '0' + s;
                }
                $('.pjax-clock').html('<p>'+H+':'+i+':'+s+'</p>' +'<p>' +d+'-'+m+'-'+y + '</p>');
            }, 1000);
        });
