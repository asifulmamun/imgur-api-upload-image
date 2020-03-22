<div class="title">
        <h1>Imgur Upload API</h1>
        <p>Use JavaScript To Upload Image</p>
    </div>
    <div class="dropzone">
        <div class="info"></div>
    </div>

    <!-- Javqa Script -->
    <script type="text/javascript">
        /*  
            **************** MAIN Function *******************
            // DONT TOUCH HERE
        */
        /* Imgur Upload Script */
        (function (root, factory) {
            "use strict";
            if (typeof define === 'function' && define.amd) {
                define([], factory);
            } else if (typeof exports === 'object') {
                module.exports = factory();
            } else {
                root.Imgur = factory();
            }
        }(this, function () {
            "use strict";
            var Imgur = function (options) {
                if (!this || !(this instanceof Imgur)) {
                    return new Imgur(options);
                }

                if (!options) {
                    options = {};
                }

                if (!options.clientid) {
                    throw 'Provide a valid Client Id here: https://api.imgur.com/';
                }

                this.clientid = options.clientid;
                this.endpoint = 'https://api.imgur.com/3/image';
                this.callback = options.callback || undefined;
                this.dropzone = document.querySelectorAll('.dropzone');
                this.info = document.querySelectorAll('.info');

                this.run();
            };

            Imgur.prototype = {
                createEls: function (name, props, text) {
                    var el = document.createElement(name), p;
                    for (p in props) {
                        if (props.hasOwnProperty(p)) {
                            el[p] = props[p];
                        }
                    }
                    if (text) {
                        el.appendChild(document.createTextNode(text));
                    }
                    return el;
                },
                insertAfter: function (referenceNode, newNode) {
                    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
                },
                post: function (path, data, callback) {
                    var xhttp = new XMLHttpRequest();

                    xhttp.open('POST', path, true);
                    xhttp.setRequestHeader('Authorization', 'Client-ID ' + this.clientid);
                    xhttp.onreadystatechange = function () {
                        if (this.readyState === 4) {
                            if (this.status >= 200 && this.status < 300) {
                                var response = '';
                                try {
                                    response = JSON.parse(this.responseText);
                                } catch (err) {
                                    response = this.responseText;
                                }
                                callback.call(window, response);
                            } else {
                                throw new Error(this.status + " - " + this.statusText);
                            }
                        }
                    };
                    xhttp.send(data);
                    xhttp = null;
                },
                createDragZone: function () {
                    var p1, p2, input;

                        p1 = this.createEls('p', {}, 'Drop Image File Here');
                        p2 = this.createEls('p', {}, 'Or click here to select image');
                    input = this.createEls('input', {type: 'file', className: 'input', accept: 'image/*'});

                    Array.prototype.forEach.call(this.info, function (zone) {
                        zone.appendChild(p1);
                        zone.appendChild(p2);
                    }.bind(this));
                    Array.prototype.forEach.call(this.dropzone, function (zone) {
                        zone.appendChild(input);
                        this.status(zone);
                        this.upload(zone);
                    }.bind(this));
                },
                loading: function () {
                    var div, table, img;

                    div = this.createEls('div', {className: 'loading-modal'});
                    table = this.createEls('table', {className: 'loading-table'});
                    img = this.createEls('img', {className: 'loading-image', src: './css/loading-spin.svg'});

                    div.appendChild(table);
                    table.appendChild(img);
                    document.body.appendChild(div);
                },
                status: function (el) {
                    var div = this.createEls('div', {className: 'status'});

                    this.insertAfter(el, div);
                },
                matchFiles: function (file, zone) {
                    var status = zone.nextSibling;

                    if (file.type.match(/image/) && file.type !== 'image/svg+xml') {
                        document.body.classList.add('loading');
                        status.classList.remove('bg-success', 'bg-danger');
                        status.innerHTML = '';

                        var fd = new FormData();
                        fd.append('image', file);

                        this.post(this.endpoint, fd, function (data) {
                            document.body.classList.remove('loading');
                            typeof this.callback === 'function' && this.callback.call(this, data);
                        }.bind(this));
                    } else {
                        status.classList.remove('bg-success');
                        status.classList.add('bg-danger');
                        status.innerHTML = 'Invalid archive';
                    }
                },
                upload: function (zone) {
                    var events = ['dragenter', 'dragleave', 'dragover', 'drop'],
                        file, target, i, len;

                    zone.addEventListener('change', function (e) {
                        if (e.target && e.target.nodeName === 'INPUT' && e.target.type === 'file') {
                            target = e.target.files;

                            for (i = 0, len = target.length; i < len; i += 1) {
                                file = target[i];
                                this.matchFiles(file, zone);
                            }
                        }
                    }.bind(this), false);

                    events.map(function (event) {
                        zone.addEventListener(event, function (e) {
                            if (e.target && e.target.nodeName === 'INPUT' && e.target.type === 'file') {
                                if (event === 'dragleave' || event === 'drop') {
                                    e.target.parentNode.classList.remove('dropzone-dragging');
                                } else {
                                    e.target.parentNode.classList.add('dropzone-dragging');
                                }
                            }
                        }, false);
                    });
                },
                run: function () {
                    var loadingModal = document.querySelector('.loading-modal');

                    if (!loadingModal) {
                        this.loading();
                    }
                    this.createDragZone();
                }
            };

            return Imgur;
        }));

        /*  
            **************** PRESENTING and EDITABLE  *******************
        */
        // Get link function print url with form and presenting form
        function feedback(res) {
            if (res.success === true) {
                var get_link = res.data.link.replace(/^http:\/\//i, 'https://');
                // show/present link with form
                document.querySelector('.status').innerHTML =
                '<br><div id="uploadForm">Image Direct Link : '+'<br><form method="POST" action="custom-processing-upload.php"><input name="link" type="text" id="link" class="image-url" value=\"'+get_link+'\"/><br><br><input type="submit" value="Submit"></form><div id="uploadImgDiv"><img id="uploadedImg" alt"uploaded" src="'+get_link+'"/></div>';
            }
        };
        // Account info and callback
        new Imgur({
            clientid: '1d2af99f7e604fb', //You can change this ClientID
            callback: feedback
        });
    </script>
    <!-- Stylesheet -->
    <style>
        @charset "UTF-8";
        /* Imgur Upload Style */
        body.loading .loading-modal {
            display: block
        }
        .title {
            margin-right: auto;
            margin-left: auto;
            text-align: center;
        }
        .dropzone {
            border: 4px dashed #ccc;
            height: 200px;
            position: relative;
            margin-right: auto;
            margin-left: auto;
            max-width: 50%;
        }
        .info {
            margin-top: 11%;
        }
        .dropzone p {
            /*height: 100%;*/
            /*line-height: 200px;*/
            margin: 0%;
            text-align: center;
            width: 100%
        }
        .input {
            height: 100%;
            left: 0;
            outline: 0;
            opacity: 0;
            position: absolute;
            top: 0;
            width: 100%
        }
        .status {
            border-radius: 5px;
            text-align: center;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }
        .image-url {
            width: 50%;
        }
        .dropzone.dropzone-dragging {
            border-color: #000
        }
        .loading-modal {
            background-color: rgba(255, 255, 255, .8);
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%
        }
        .loading-table {
            margin-left: auto;
            margin-right: auto;
            margin-top: 15%;
            margin-bottom: 15%;
        }
        .img {
            width: 100%;
        }

        /* Mobile view */
        @media screen and (min-width: 300px) and (max-width: 700px) {
            .dropzone {
                max-width: 80%;
            }
            .info {
                margin-top: 30%;
            }
            .status {
                width: 80%;
            }
            .image-url {
                width: 80%;
            }
            .loading-table {
                margin-left: auto;
                margin-right: auto;
                margin-top: 50%;
                margin-bottom: 50%;
            }
        } /* Mobile View END MAX 700px */
    </style>