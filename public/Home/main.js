//Function for update the file table list info
function updateTotalInfo(){
    // Get the selected files
    const selectedFiles = $('#file-data')[0].files;
    $('#selectedFilesList').empty();
        if (selectedFiles.length > 0) {
          for (let i = 0; i < selectedFiles.length; i++) {
            const fileName = selectedFiles[i].name;
            const listItem = $('<li data-filename="'+ fileName +'"><div class="file-name"><span>' + fileName + '</span><span type="button" class="delete-button" title="Delete"><i class="fa-regular fa-trash-can"></i></span></div></li>');
            $('#selectedFilesList').append(listItem);
          }
    }
    // Calculate the total number of files and total size
    const totalFiles = selectedFiles.length;
    let totalSize = 0;
    for (let i = 0; i < totalFiles; i++) {
        totalSize += selectedFiles[i].size;
    }
    // Convert total size to MB and display the information
    const totalSizeMB = (totalSize / 1048576).toFixed(2);
    $('#fileInfo').text('Total ' + totalFiles + ' files • ' + totalSizeMB + 'MB');

    if(totalFiles == 0){
        EmptyList();
    }
}
// store the file to global use
let oldfilename;
// store the interval ID to global use
let countdownInterval;
const duration = 600; // 10 minutes in seconds
//Function for the valid time show
function updateTimer() {
    const now = new Date().getTime();
    const targetTime = now + duration * 1000;

    countdownInterval = setInterval(function () {
        const currentTime = new Date().getTime();
        const remainingTime = Math.max(0, targetTime - currentTime);

        const minutes = Math.floor(remainingTime / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        const formattedSeconds = seconds < 10 ? '0' + seconds : seconds;

        if (remainingTime <= 0) {
            clearInterval(countdownInterval);
            $("#sending-button-icon").text("Valid for: 00:00 Minutes").attr("title", "Key Expired.");
            $(".receive-key").css("opacity", 0.5).attr("title", "Key Expired.");
        } else {
            $("#sending-button-icon").text("Valid for: " + formattedMinutes + ':' + formattedSeconds + " Minutes").attr("title", "Use key before expired.");
        }
    }, 1000);
}

//Function for empty file list
function EmptyList(){
    clearInterval(countdownInterval);
    $(".file-list-box").hide();
    $(".receive-key").hide();
    $("#addmore").hide();
    $("#sending-button-icon").hide();
    $(".send-icon").css("display", "block");
    $("#send-button-icon").css("display", "block");
    $('#file-data').val('');
    $("#sending-button-icon").html("Sending... <i class='fa-regular fa-paper-plane fa-bounce fa-xl'></i>").attr("title", "Sending");;
}


$(document).ready(function(){
    //ajax setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //swal fire ready for real time notify
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-start',
        showConfirmButton: false,
        timer: 4000,
        background: '#1B1212',
        color: 'white',
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    //send jquery start
    $("#file-data").on('change',function(event) {
        $(".send-icon").hide();
        $(".receive-key").hide();
        $("#addmore").css("display", "block");
        $(".file-list-box").css("display", "block");
        $("#selectedFilesList").css("display", "block");
        $("#reset-data").text("Reset").css({"color":"#a43c1c"});
        $("#sending-button-icon").html("Sending... <i class='fa-regular fa-paper-plane fa-bounce fa-xl'></i>");

        const selectedFiles = event.target.files;
        oldfilename = selectedFiles;
        // Calculate the total number of files,total size,file list
        updateTotalInfo();
    });

    //For add more file
    $('#addmore').click(function() {
        $('#more-file-data').click();
    });

    $("#more-file-data").on('change',function(){
        const newFileList = new DataTransfer();
        for (let i = 0; i < oldfilename.length; i++) {
            const file = oldfilename[i];
            newFileList.items.add(file);
        }
        const fileInput = document.getElementById('more-file-data');
        const fileInputFiles = fileInput.files;
        const selectedFiles = Array.from(fileInputFiles);
        const filteredFiles = selectedFiles.filter(function(file) {
            let isDuplicate = false;
            for (let i = 0; i < oldfilename.length; i++) {
                if (file.name === oldfilename[i].name) {
                    isDuplicate = true;
                    break;
                }
            }
            return !isDuplicate;
        });

        filteredFiles.forEach(file => {
            oldfilename = [...oldfilename, file];
            newFileList.items.add(file);
        });
        const allInput = document.getElementById('file-data');
        allInput.files = newFileList.files;
        updateTotalInfo();
    });
    //delete file from file list
    $(document).on('click', '.delete-button', function() {
        const listItem = $(this).closest('li');
        const fileName = listItem.data('filename');
        listItem.remove();

        const fileInput = document.getElementById('file-data');
        const fileInputFiles = fileInput.files;
        const selectedFiles = Array.from(fileInputFiles);
        const filteredFiles = selectedFiles.filter(file => file.name !== fileName);
        oldfilename = selectedFiles.filter(file => file.name !== fileName);
        const newFileList = new DataTransfer();
        filteredFiles.forEach(file => {
            newFileList.items.add(file);
        });
        fileInput.files = newFileList.files;
        updateTotalInfo();
    });

    //for reset full file list
    $("#reset-data").click(function () {
        EmptyList();
    });

    //file sending jquery
    $("#send-form").submit(function (event1) {
        event1.preventDefault();
        $("#send-button-icon").hide();
        $("#sending-button-icon").css("display", "block");
        clearInterval(countdownInterval);
        var form = new FormData(this);
        var url = $(this).attr('action');
        //ajax for file send and receive the key
        $.ajax({
            type: "POST",
            url: url,
            data: form,
            contentType: false,
            processData: false,
            success: function (data) {
                if(data.is_error == 1){
                    Toast.fire({
                        icon: 'error',
                        title: ''+ data.error +''
                    })
                    $("#sending-button-icon").hide();
                    $("#send-button-icon").css("display", "block");
                }else{
                    updateTimer();
                    $("#addmore").hide();
                    $("#selectedFilesList").hide();
                    $(".receive-key").css({"display":"block","opacity":1}).attr("title", "Click to Copy key");
                    $("#key").text(data.receive_key);
                    $("#reset-data").text("Send again").css({"color":"#274d8a"});
                    Toast.fire({
                        icon: 'success',
                        title: 'Send Successfully.'
                    })
                }
            },
            error: function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Something error.Try again.'
                })
                EmptyList();
            }
        });
    });


    //click key to copy receive key
    $("#key").click(function () {
        var copyText = document.getElementById("key");
        var textArea = document.createElement("textarea");
        textArea.value = copyText.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("Copy");
        textArea.remove();
        Toast.fire({
            icon: 'success',
            title: 'Copied to Clipboard.'
        })
    });

    //receive jquery start
    $("#receive-form").submit(function (event2) {
        event2.preventDefault();
        $(".download-icon").hide();
        $("#spinner-icon").css("display", "block");
        var receiveKey = $("#receive_key").val();
        var url = $(this).attr('action');
        //post the receive key to backend
        $.ajax({
            type: "POST",
            url: url,
            data: {
                receive_key: receiveKey
            },
            success: function (data) {
                if(data.is_error == 1){
                    Toast.fire({
                        icon: 'error',
                        title: ''+ data.error +''
                    })
                    $("#receive-form").trigger("reset");
                    $("#spinner-icon").hide();
                    $(".download-icon").css("display", "block");
                }else if(data.fileexist == 1){
                    var url = $('#receive_key').data('url');
                    var url = url + '/' + receiveKey;//share/123456
                    //get the file for download(we used two ajax to download file without page reload)
                    $.ajax({
                        url: url,
                        method: "GET",
                        xhrFields: {
                            responseType: "blob",
                        },
                        success: function (filedata) {
                            // Create a blob URL for the downloaded file to with automatic trigger
                            var blobUrl = URL.createObjectURL(filedata);
                            var downloadAnchor = $("<a/>", {
                                style: "display: none",
                                href: blobUrl,
                                download: data.FileName,//file name get from backend as return with previous ajax
                            });
                            $("body").append(downloadAnchor);
                            downloadAnchor[0].click();
                            downloadAnchor.remove();
                            $("#receive-form").trigger("reset");
                            $("#spinner-icon").hide();
                            $(".download-icon").css("display", "block");
                            Toast.fire({
                                icon: 'success',
                                title: 'Downloaded Successfully.'
                            })
                        },
                        error: function () {
                            $("#receive-form").trigger("reset");
                            $("#spinner-icon").hide();
                            $(".download-icon").css("display", "block");
                            Toast.fire({
                                icon: 'error',
                                title: 'Something error.Try again.'
                            })
                        },
                    });
                    // get file ajax end
                }
                else{
                    $("#receive-form").trigger("reset");
                    $("#spinner-icon").hide();
                    $(".download-icon").css("display", "block");
                    Toast.fire({
                        icon: 'error',
                        title: 'Something error.Try again.'
                    })
                }
            },
            error: function() {
                $("#receive-form").trigger("reset");
                $("#spinner-icon").hide();
                $(".download-icon").css("display", "block");
                Toast.fire({
                    icon: 'error',
                    title: 'Something error.Try again.'
                })
            }
        });
    });
});
