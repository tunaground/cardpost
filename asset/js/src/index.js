const hello = require('./hello');

$.ajax({
    url: "/api/getWeather",
    data: {
        zipcode: 97201
    },
    success: function( result ) {
        console.log(result);
    },
    complete: function (xhr, status) {
        console.log('dead');
    }
});

hello('world');