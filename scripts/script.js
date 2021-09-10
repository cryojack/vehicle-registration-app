/*
*   This is the main JS/JQuery file for all .php pages.
*   This file contains sections which describe what functions are used on what page.
*
*/

/*
*
*   Functions for 'index.php' to add vehicles
*
*/

$("#insertVehicle").on('submit', function (e) {
    e.preventDefault(); 

    function showError (elemID,errorID,errorMsg) {
        $("#" + elemID).css("border", "solid 2px red");
        $("#" + errorID).text(errorMsg);
    }

    function checkErrors (error) {
        $.each(error, function (index, value) {
            switch (index) {
                case 'empty':
                    $("#submitErr").text(value);
                    break;

                case 'sqlerror':
                    $("#submitErr").text(value);
                    break;

                case 'connerror':
                    $("#submitErr").text(value);
                    break;
    
                case 'name':
                    showError("vehicleName", "nameErr", value)
                    break;

                case 'model':
                    showError("vehicleModel", "modelErr", value)
                    break;

                case 'owner':
                    showError("ownerName", "ownerErr", value)
                    break;

                case 'date':
                    showError("datePurchased", "dateErr", value)
                    break;

                case 'price':
                    showError("purchasePrice", "priceErr", value)
                    break;

                default:
                    $("#submitErr").text("Something went wrong!");
                    break;
            }
        });
    }

    var form = $(this),
        url = form.attr('action'),
        type = form.attr('method'),
        formdata = {};

    form.find('[name]').each(function (i, value) {
        var input = $(this),
            name = input.attr('name');
            value = input.val();
            formdata[name] = value;
        input.removeAttr('style');
    });

    form.find('[class=error-box]').each(function (i) {
        $(this).text("");
    });

    $.ajax({
        url : url,
        type: type,
        data: formdata
    }).done(function (response) {
        var resp = JSON.parse(response);
        if (resp.errors) {
            checkErrors(resp.errors);
        }
        if (resp.success === true) {
            $("#submitErr").css("color", "green");
            $("#submitErr").text(resp.inserted);
            form.find('input').each(function (i,value) {
                $(this).val("");
            });
        } else {
            $("#submitErr").text(resp.inserted);
        }
    });
});

/*
*
*   Functions for 'add-service-details.php' to add service jobs.
*
*/

$("#insertJob").on('submit', function (e) {
    e.preventDefault(); 

    function showError (elemID,errorID,errorMsg) {
        $("#" + elemID).css("border", "solid 2px red");
        $("#" + errorID).text(errorMsg);
    }

    function checkErrors (error) {
        $.each(error, function (index, value) {
            switch (index) {
                case 'empty':
                    $("#submitErr").text(value);
                    break;

                case 'sqlerror':
                    $("#submitErr").text(value);
                    break;

                case 'connerror':
                    $("#submitErr").text(value);
                    break;

                case 'name':
                    showError("vehicleDetails", "detailsErr", value)
                    break;

                case 'number':
                    showError("serviceJobNo", "jobNumErr", value)
                    break;

                case 'date':
                    showError("serviceDate", "jobDateErr", value)
                    break;

                default:
                    $("#submitErr").text("Something went wrong!");
                    break;
            }
        });
    }

    var form = $(this),
        url = form.attr('action'),
        type = form.attr('method'),
        formdata = {};

    form.find('[name]').each(function (i, value) {
        var input = $(this),
            name = input.attr('name');
            value = input.val();
            formdata[name] = value;
        input.removeAttr('style');
    });

    form.find('[class=error-box]').each(function (i) {
        $(this).text("");
    });

    $.ajax({
        url : url,
        type: type,
        data: formdata
    }).done(function (response) {
        var resp = JSON.parse(response);
        if (resp.errors) {
            checkErrors(resp.errors);
        }
        if (resp.success === true) {
            $("#submitErr").css("color", "green");
            $("#submitErr").text(resp.inserted);
            form.find('input').each(function (i,value) {
                $(this).val("");
            });
        } else {
            $("#submitErr").text(resp.inserted);
        }
    });
});

/*
*
*   Functions for retrieving vehicle data to add services
*   on 'add-service-details.php' page
*
*/

$("#vehicleDetails").one('mouseover', function () {
    var vehicleDetails = $(this).attr('name');

    $.ajax({
        url     :   "includes/get-vehicle-data.php",
        type    :   "POST",
        dataType:   "json",
        data    :   vehicleDetails
    }).done(function (data) {
        $.each(data, function (i) {
            $("#vehicleDetails").append("<option value=" + data[i].vname + ">" + data[i].vname + "</option>");
        });
    });
});

$("#vehicleDetails").on('change', function () {
    var serv = $("#serviceJobNo"),
        vehicleName = $(this).val();

    serv.html("");
    serv.append("<option>Please select...</option>");

    $.ajax({
        url     :   "includes/get-vehicle-data.php",
        type    :   "POST",
        dataType:   "json",
        data    :   {
            serviceJobNo: vehicleName
        }
    }).done(function(data) {
        if (data) {
            $.each(data, function (i) {
                serv.append("<option value="+ data[i].jobnum +">"+ data[i].jobnum +"</option>");
            });
        } else {
            serv.append("<option>No jobs found</option>");
        }
    });
});

/*
*
*   Miscellaneous functions, this one is to hide the job-section div 
*   on 'search-vehicle.php' until clicks on a vehicle's details button.
*
*/

$(".job-section").hide();
$(".service-section").hide();

/*
*
*   Functions for 'search-vehicle.php' to get vehicle data
*   along with service details.
*
*/

$("#searchForm").on('keyup', function (e) {
    e.preventDefault();
    var form = $(this),
        url = form.attr('action'),
        type = form.attr('method'),
        result = $("#showResults ul");

    var getDetails = $.ajax({
        url : url,
        type: type,
        dataType: "json",
        data: {
            searchVehicle   : $("#searchVehicle").val()
        }
    });

    result.html("");
    $(".job-section").hide();
    $(".job-section").html("");
    $(".service-section").hide();
    $(".service-section").html("");

    getDetails.done(function (data) {
        $.each(data, function (i) {
            result.append("<li>"+ data[i].vname +"</br><button id='getVehicleDetails' name="+ data[i].vid +">View details</button><button id='getVehicleJobs' name="+ data[i].vid +">View jobs</button></li>");
        });
    });
});

/*
*
*   Functions for 'search-vehicle.php' to get all service jobs 
*   for a vehicle and add services to a job.
*
*/

$("#showResults").on('click', '#getVehicleDetails', function () {
    var name = $(this).attr("name"),
        url = "includes/get-vehicle-data.php",
        type = "POST";

    $.ajax({
        url : url,
        type: type,
        data: {
            vehicleName : name
        }
    }).done(function (data) {
        var d = JSON.parse(data);
        $(".job-section").show();
        $(".job-section").html("");
        $("html").animate({ scrollTop : $(".job-section").offset().top }, 800);
        $(".job-section").append("<ul></ul>");
        $.each(d, function(i) {
            $(".job-section ul").append("<li>Name : "+d[i].vname+"</li>");
            $(".job-section ul").append("<li>Type : "+d[i].vtype+"</li>");
            $(".job-section ul").append("<li>Model : "+d[i].vmodel+"</li>");
            $(".job-section ul").append("<li>Date purchased : "+d[i].vdate+"</li>");
            $(".job-section ul").append("<li>Price : "+d[i].vprice+"</li>");
            $(".job-section ul").append("<li>Owner : "+d[i].vowner+"</li>");
            $(".job-section ul").append("<li>Date added : "+d[i].vdateadd+"</li>");
        });
    });
});

$("#showResults").on('click', '#getVehicleJobs', function () {
    var name = $(this).attr("name"),
        url = "includes/get-vehicle-data.php",
        type = "POST";

    $.ajax({
        url : url,
        type: type,
        data: {
            jobNumber : name
        }
    }).done(function (data) {
        var d = JSON.parse(data);
        $(".job-section").show();
        $(".job-section").html("");
        $("html").animate({ scrollTop : $(".job-section").offset().top }, 800);
        $(".job-section").append("<ul></ul>");
        if (d.success === true) {
            $.each(d.columns, function (i) {
                $(".job-section ul").append("<li>Job Number : "+ d.columns[i].jobno +"<button id='getJobDetails' name="+d.columns[i].jobno+">View Details</button><button id='viewJobServices' name="+d.columns[i].jobno+">View Services</button></li>");
            });
        } else {
            $(".job-section ul").append("<li>"+d.message+"</li>");
        }
    });
});

$(".job-section").on('click',"#getJobDetails", function () {
    var name = $(this).attr("name"),
        url = "includes/get-vehicle-data.php",
        type = "POST";

    $.ajax({
        url : url,
        type: type,
        data: {
            jobDetails : name
        }
    }).done(function (data) {
        var d = JSON.parse(data);
        $(".service-section").show();
        $(".service-section").html("");
        $("html").animate({ scrollTop : $(".service-section").offset().top }, 800);
        $(".service-section").append("<ul></ul>");
        $.each(d, function(i) {
            $(".service-section ul").append("<li>Job details for "+ d[i].jobno +"</li>");
            $(".service-section ul").append("<li>Assigned to : "+ d[i].vname +"</li>");
            $(".service-section ul").append("<li>Job Price : "+ d[i].jobprice +"</li>");
            $(".service-section ul").append("<li>Job Date : "+ d[i].jobdate +"</li>");
        });
    });
});

$(".job-section").on('click',"#viewJobServices", function () {
    var name = $(this).attr("name"),
        url = "includes/get-vehicle-data.php",
        type = "POST";

    $.ajax({
        url : url,
        type: type,
        data: {
            jobServices : name
        }
    }).done(function (data) {
        var d = JSON.parse(data);
        $(".service-section").show();
        $(".service-section").html("");
        $("html").animate({ scrollTop : $(".service-section").offset().top }, 800);
        $(".service-section").append("<ul></ul>");
        if (d.success === true) {
            $(".service-section ul").append("<li>List of services on job number "+ d.jobno +"</li>");
            $(".service-section ul").append("<li><table></table></li>");
            $(".service-section ul li table").append("<tr></tr>");
            $(".service-section ul li table").append("<th>Service Detail</th>");
            $(".service-section ul li table").append("<th>Service Price</th>");
            $.each(d.columns, function(i) {
                $(".service-section ul li table").append("<tr></tr>");
                $(".service-section ul li table").append("<td>"+ d.columns[i].srvdtl +"</td>");
                $(".service-section ul li table").append("<td>"+ d.columns[i].srvprice +"</td>");
                $(".service-section ul li table").append("<td><button id='deleteService' name="+d.columns[i].srvid+">Delete</button></td>");
            });
        } else {
            $(".service-section ul").append("<li>"+d.message+"</li>");
        }
    });
});

$("#insertService").on('submit', function (e) {
    e.preventDefault();

    function checkErrors (error) {
        $.each(error, function (index, value) {
            switch (index) {
                case 'empty':
                    $("#submitErr").text(value);
                    break;

                case 'sqlerror':
                    $("#submitErr").text(value);
                    break;

                case 'connerror':
                    $("#submitErr").text(value);
                    break;

                default:
                    $("#submitErr").text("Something went wrong!");
                    break;
            }
        });
    }

    var form = $(this),
        url = form.attr('action'),
        type = form.attr('method'),
        formdata = {};

    form.find('[name]').each(function (i, value) {
        var input = $(this),
            name = input.attr('name');
            value = input.val();
            formdata[name] = value;
        input.removeAttr('style');
    });

    form.find('[class=error-box]').each(function (i,value) {
        $(this).text("");
    });

    $.ajax({
        url : url,
        type: type,
        data: formdata
    }).done(function (response) {
        var resp = JSON.parse(response);
        if (resp.errors) {
            checkErrors(resp.errors);
        }
        if (resp.success === true) {
            $("#submitErr").css("color", "green");
            $("#submitErr").text(resp.inserted);
            form.find('input').each(function (i,value) {
                $(this).val("");
            });
        } else {
            $("#submitErr").text(resp.inserted);
        }
    });
});

$(".service-section").on('click', "#deleteService", function () {
    var name = $(this).attr("name"),
        url = "includes/get-vehicle-data.php",
        type = "POST";

    $.ajax({
        url : url,
        type: type,
        data: {
            deleteService : name
        }
    }).done(function (data) {
        var d = JSON.parse(data);
        $(".service-section").html("");
        $(".service-section").append("<ul></ul>");
        if (d[0].success === true) {
            $(".service-section ul").append("<li>List of services on job number "+ d[0].jobno +"</li>");
            $(".service-section ul").append("<li><table></table></li>");
            $(".service-section ul li table").append("<tr></tr>");
            $(".service-section ul li table").append("<th>Service Detail</th>");
            $(".service-section ul li table").append("<th>Service Price</th>");
            $.each(d[0].columns, function (i) {
                $(".service-section ul li table").append("<tr></tr>");
                $(".service-section ul li table").append("<td>"+ d[0].columns[i].srvdtl +"</td>");
                $(".service-section ul li table").append("<td>"+ d[0].columns[i].srvprice +"</td>");
                $(".service-section ul li table").append("<td><button id='deleteService' name="+d[0].columns[i].srvid+">Delete</button></td>");
            });
            $(".service-section").append("<div class='error-box' id='servErr'>Service deleted successfully</div>");
        } else {
            $(".service-section ul").append("<li>"+d[0].message+"</li>");
        }
    });
});