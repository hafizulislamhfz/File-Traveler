   {{-- file share section start --}}
    <section class="file-share">
        <div class="file"  data-aos="zoom-in">
            <div class="send box">
                <form action="{{ url('/store_file') }}" method="post" id="send-form" enctype="multipart/form-data">
                    @csrf
                    <span class="title">Send <span id="addmore"><i class="fa-solid fa-file-circle-plus fa-xl" title="Add more files"></i></span></span>
                    <input type="file" name="" id="more-file-data" hidden multiple>
                    <div class="send-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                            <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                        </svg>
                        <input type="file" name="files[]" id="file-data" multiple >
                    </div>
                    <div class="file-list-box">
                        <div class="file-list">
                            <div class="file-info">
                                <p>
                                    <span id="fileInfo"></span>
                                    <a type="button" id="reset-data">Reset</a>
                                </p>
                            </div>
                            <ul id="selectedFilesList">

                            </ul>
                            <div class="receive-key" title="Click to Copy">
                                <span id="key">ERROR</span>
                            </div>
                        </div>
                        <div class="send-button">
                            <button type="submit" title="Send" id="send-button-icon">
                                <i class="fa-regular fa-paper-plane fa-xl"></i>
                            </button>
                            <button type="button" title="Sending" id="sending-button-icon">
                                Sending... <i class="fa-regular fa-paper-plane fa-bounce fa-xl"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="receive box">
                <span class="title">Receive</span>
                <form action="{{ url('/share_file') }}" method="post" id="receive-form">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="receive_key" id="receive_key" data-url="{{ url('/share') }}" placeholder="Enter receive key">
                        <button type="submit" class="btn btn-outline-secondary" id="download-button"  title="Receive">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-file-earmark-arrow-down download-icon" viewBox="0 0 16 16">
                                <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            </svg>
                            <i class="fa-solid fa-circle-notch fa-spin fa-xl" id="spinner-icon" title="Receiving"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    {{-- file share section end --}}

