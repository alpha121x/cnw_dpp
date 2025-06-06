/* See External References - http://grgichtran.com/code/js/jqueryrotate.min.js */
/*
Lightbox v2.6
by Lokesh Dhakar - http://www.lokeshdhakar.com

For more information, visit:
http://lokeshdhakar.com/projects/lightbox2/

Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
- free for use in both personal and commercial projects
- attribution requires leaving author name, author link, and the license info intact
*/


(function() {
    var $, Lightbox, LightboxOptions;

    $ = jQuery;

    LightboxOptions = (function() {
        function LightboxOptions() {
            this.fadeDuration = 500;
            this.fitImagesInViewport = true;
            this.resizeDuration = 700;
            this.showImageNumberLabel = true;
            this.wrapAround = false;
        }

        LightboxOptions.prototype.albumLabel = function(curImageNum, albumSize) {
            return "Image " + curImageNum + " of " + albumSize;
        };

        return LightboxOptions;

    })();

    Lightbox = (function() {
        function Lightbox(options) {
            this.options = options;
            this.album = [];
            this.currentImageIndex = void 0;
            this.init();
        }

        Lightbox.prototype.init = function() {
            this.enable();
            return this.build();
        };

        Lightbox.prototype.enable = function() {
            var _this = this;
            return $('body').on('click', 'a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]', function(e) {
                _this.start($(e.currentTarget));
                return false;
            });
        };

        Lightbox.prototype.build = function() {
            var _this = this;
            $("<div id='lightboxOverlay' class='lightboxOverlay'></div><div id='lightbox' class='lightbox'><div class='lb-outerContainer'><div class='lb-container'><img class='lb-image' src='' /><div class='lb-nav'><a class='lb-prev' href='' ></a><a class='lb-next' href='' ></a></div><div class='lb-loader'><a class='lb-cancel'></a></div></div></div><div class='lb-dataContainer'><div class='lb-data'><div class='lb-details'><span class='lb-caption'></span><span class='lb-number'></span></div><div class='lb-closeContainer'><a class='lb-close'></a><a class='lb-rotate'></a></div></div></div></div>").appendTo($('body'));
            this.$lightbox = $('#lightbox');
            this.$overlay = $('#lightboxOverlay');
            this.$outerContainer = this.$lightbox.find('.lb-outerContainer');
            this.$container = this.$lightbox.find('.lb-container');
            this.containerTopPadding = parseInt(this.$container.css('padding-top'), 10);
            this.containerRightPadding = parseInt(this.$container.css('padding-right'), 10);
            this.containerBottomPadding = parseInt(this.$container.css('padding-bottom'), 10);
            this.containerLeftPadding = parseInt(this.$container.css('padding-left'), 10);
            this.$overlay.hide().on('click', function() {
                _this.end();
                return false;
            });
            this.$lightbox.hide().on('click', function(e) {
                if ($(e.target).attr('id') === 'lightbox') {
                    _this.end();
                }
                return false;
            });
            this.$outerContainer.on('click', function(e) {
                if ($(e.target).attr('id') === 'lightbox') {
                    _this.end();
                }
                return false;
            });
            this.$lightbox.find('.lb-prev').on('click', function() {
                if (_this.currentImageIndex === 0) {
                    _this.changeImage(_this.album.length - 1);
                } else {
                    _this.changeImage(_this.currentImageIndex - 1);
                }
                return false;
            });
            this.$lightbox.find('.lb-next').on('click', function() {
                if (_this.currentImageIndex === _this.album.length - 1) {
                    _this.changeImage(0);
                } else {
                    _this.changeImage(_this.currentImageIndex + 1);
                }
                return false;
            });
            this.$lightbox.find('.lb-rotate').on('click', function() {
                $cont = _this.$lightbox.find('.lb-outerContainer');
                $image = _this.$lightbox.find('.lb-image');

                if ($($cont).attr('angle') == null) {
                    $($cont).attr('angle', 0);
                }
                var value = Number($($cont).attr('angle'));
                value += 90;

                $($cont).rotate({ animateTo:value});
                $($cont).attr('angle', value);

                return false;
            });

            return this.$lightbox.find('.lb-loader, .lb-close').on('click', function() {
                _this.end();
                return false;
            });
        };

        Lightbox.prototype.start = function($link) {
            var $window, a, dataLightboxValue, i, imageNumber, left, top, _i, _j, _len, _len1, _ref, _ref1;
            $(window).on("resize", this.sizeOverlay);
            $('select, object, embed').css({
                visibility: "hidden"
            });
            this.$overlay.width($(document).width()).height($(document).height()).fadeIn(this.options.fadeDuration);
            this.album = [];
            imageNumber = 0;
            dataLightboxValue = $link.attr('data-lightbox');
            if (dataLightboxValue) {
                _ref = $($link.prop("tagName") + '[data-lightbox="' + dataLightboxValue + '"]');
                for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
                    a = _ref[i];
                    this.album.push({
                        link: $(a).attr('href'),
                        title: $(a).attr('title')
                    });
                    if ($(a).attr('href') === $link.attr('href')) {
                        imageNumber = i;
                    }
                }
            } else {
                if ($link.attr('rel') === 'lightbox') {
                    this.album.push({
                        link: $link.attr('href'),
                        title: $link.attr('title')
                    });
                } else {
                    _ref1 = $($link.prop("tagName") + '[rel="' + $link.attr('rel') + '"]');
                    for (i = _j = 0, _len1 = _ref1.length; _j < _len1; i = ++_j) {
                        a = _ref1[i];
                        this.album.push({
                            link: $(a).attr('href'),
                            title: $(a).attr('title')
                        });
                        if ($(a).attr('href') === $link.attr('href')) {
                            imageNumber = i;
                        }
                    }
                }
            }
            $window = $(window);
            top = $window.scrollTop() + $window.height() / 10;
            left = $window.scrollLeft();
            this.$lightbox.css({
                top: top + 'px',
                left: left + 'px'
            }).fadeIn(this.options.fadeDuration);
            this.changeImage(imageNumber);
        };

        Lightbox.prototype.changeImage = function(imageNumber) {
            var $image, preloader,
                _this = this;
            this.disableKeyboardNav();
            $image = this.$lightbox.find('.lb-image');

            this.sizeOverlay();
            this.$overlay.fadeIn(this.options.fadeDuration);
            $('.lb-loader').fadeIn('slow');
            this.$lightbox.find('.lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption').hide();
            this.$outerContainer.addClass('animating');
            preloader = new Image();
            preloader.onload = function() {
                var $preloader, imageHeight, imageWidth, maxImageHeight, maxImageWidth, windowHeight, windowWidth;
                $image.attr('src', _this.album[imageNumber].link);
                $preloader = $(preloader);
                $image.width(preloader.width);
                $image.height(preloader.height);
                if (_this.options.fitImagesInViewport) {
                    windowWidth = $(window).width();
                    windowHeight = $(window).height();
                    maxImageWidth = windowWidth - _this.containerLeftPadding - _this.containerRightPadding - 20;
                    maxImageHeight = windowHeight - _this.containerTopPadding - _this.containerBottomPadding - 110;
                    if ((preloader.width > maxImageWidth) || (preloader.height > maxImageHeight)) {
                        if ((preloader.width / maxImageWidth) > (preloader.height / maxImageHeight)) {
                            imageWidth = maxImageWidth;
                            imageHeight = parseInt(preloader.height / (preloader.width / imageWidth), 10);
                            $image.width(imageWidth);
                            $image.height(imageHeight);
                        } else {
                            imageHeight = maxImageHeight;
                            imageWidth = parseInt(preloader.width / (preloader.height / imageHeight), 10);
                            $image.width(imageWidth);
                            $image.height(imageHeight);
                        }
                    }
                }
                return _this.sizeContainer($image.width(), $image.height());
            };
            preloader.src = this.album[imageNumber].link;
            this.currentImageIndex = imageNumber;
        };

        Lightbox.prototype.sizeOverlay = function() {
            return $('#lightboxOverlay').width($(document).width()).height($(document).height());
        };

        Lightbox.prototype.sizeContainer = function(imageWidth, imageHeight) {
            var newHeight, newWidth, oldHeight, oldWidth,
                _this = this;
            oldWidth = this.$outerContainer.outerWidth();
            oldHeight = this.$outerContainer.outerHeight();
            newWidth = imageWidth + this.containerLeftPadding + this.containerRightPadding;
            newHeight = imageHeight + this.containerTopPadding + this.containerBottomPadding;
            this.$outerContainer.animate({
                width: newWidth,
                height: newHeight
            }, this.options.resizeDuration, 'swing');
            setTimeout(function() {
                _this.$lightbox.find('.lb-dataContainer').width(newWidth);
                _this.$lightbox.find('.lb-prevLink').height(newHeight);
                _this.$lightbox.find('.lb-nextLink').height(newHeight);
                _this.showImage();
            }, this.options.resizeDuration);
        };

        Lightbox.prototype.showImage = function() {
            this.$lightbox.find('.lb-loader').hide();
            this.$lightbox.find('.lb-image').fadeIn('slow');
            this.updateNav();
            this.updateDetails();
            this.preloadNeighboringImages();
            this.enableKeyboardNav();
        };

        Lightbox.prototype.updateNav = function() {
            this.$lightbox.find('.lb-nav').show();
            if (this.album.length > 1) {
                if (this.options.wrapAround) {
                    this.$lightbox.find('.lb-prev, .lb-next').show();
                } else {
                    if (this.currentImageIndex > 0) {
                        this.$lightbox.find('.lb-prev').show();
                    }
                    if (this.currentImageIndex < this.album.length - 1) {
                        this.$lightbox.find('.lb-next').show();
                    }
                }
            }
        };

        Lightbox.prototype.updateDetails = function() {
            var _this = this;
            if (typeof this.album[this.currentImageIndex].title !== 'undefined' && this.album[this.currentImageIndex].title !== "") {
                this.$lightbox.find('.lb-caption').html(this.album[this.currentImageIndex].title).fadeIn('fast');
            }
            if (this.album.length > 1 && this.options.showImageNumberLabel) {
                this.$lightbox.find('.lb-number').text(this.options.albumLabel(this.currentImageIndex + 1, this.album.length)).fadeIn('fast');
            } else {
                this.$lightbox.find('.lb-number').hide();
            }
            this.$outerContainer.removeClass('animating');
            this.$lightbox.find('.lb-dataContainer').fadeIn(this.resizeDuration, function() {
                return _this.sizeOverlay();
            });
        };

        Lightbox.prototype.preloadNeighboringImages = function() {
            var preloadNext, preloadPrev;
            if (this.album.length > this.currentImageIndex + 1) {
                preloadNext = new Image();
                preloadNext.src = this.album[this.currentImageIndex + 1].link;
            }
            if (this.currentImageIndex > 0) {
                preloadPrev = new Image();
                preloadPrev.src = this.album[this.currentImageIndex - 1].link;
            }
        };

        Lightbox.prototype.enableKeyboardNav = function() {
            $(document).on('keyup.keyboard', $.proxy(this.keyboardAction, this));
        };

        Lightbox.prototype.disableKeyboardNav = function() {
            $(document).off('.keyboard');
        };

        Lightbox.prototype.keyboardAction = function(event) {
            var KEYCODE_ESC, KEYCODE_LEFTARROW, KEYCODE_RIGHTARROW, key, keycode;
            KEYCODE_ESC = 27;
            KEYCODE_LEFTARROW = 37;
            KEYCODE_RIGHTARROW = 39;
            keycode = event.keyCode;
            key = String.fromCharCode(keycode).toLowerCase();
            if (keycode === KEYCODE_ESC || key.match(/x|o|c/)) {
                this.end();
            } else if (key === 'p' || keycode === KEYCODE_LEFTARROW) {
                if (this.currentImageIndex !== 0) {
                    this.changeImage(this.currentImageIndex - 1);
                }
            } else if (key === 'n' || keycode === KEYCODE_RIGHTARROW) {
                if (this.currentImageIndex !== this.album.length - 1) {
                    this.changeImage(this.currentImageIndex + 1);
                }
            }
        };

        Lightbox.prototype.end = function() {
            this.disableKeyboardNav();
            $(window).off("resize", this.sizeOverlay);
            this.$lightbox.fadeOut(this.options.fadeDuration);
            this.$overlay.fadeOut(this.options.fadeDuration);
            return $('select, object, embed').css({
                visibility: "visible"
            });
        };

        return Lightbox;

    })();

    $(function() {
        var lightbox, options;
        options = new LightboxOptions();
        return lightbox = new Lightbox(options);
    });

}).call(this);