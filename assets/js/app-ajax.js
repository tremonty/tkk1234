"use strict";
(function($) {

	function SearchBox (form) {
		this.form = form;
		this.listing = $('body').hasClass('stm-template-listing') || $('body').hasClass('stm-template-aircrafts');
		this.init();
	}

	SearchBox.prototype.init = function () {
		var timer, self = this;
		$('select:not(.hide)', this.form).select2().on('change', function () {
			if (timer) {
				clearTimeout(timer);
			}

			timer = setTimeout($.proxy(self.performAjax, self), 300);
		});
	};

	SearchBox.prototype.fragments = function () {
		return 'total' + (!this.listing ? ',options' : '');
	};

	SearchBox.prototype.performAjax = function () {
		var $_form = $(this.form);
		$.ajax({
			url: $_form.attr('action'),
			dataType: 'json',
			context: this,
			data: $_form.serialize() + '&ajax_action=listings-binding&fragments=' + this.fragments(),
			beforeSend: $.proxy(this.beforeAjax, this),
			success: $.proxy(this.success, this),
			complete: $.proxy(this.complete, this)
		});
	};

	SearchBox.prototype.beforeAjax = function () {
		if(!this.listing) {
			$('select, button[type="submit"]', this.form).prop("disabled", true);
			$('.select2-container--default .select2-selection--single .select2-selection__arrow b', this.form).addClass('stm-preloader');
		}

		if($('body').hasClass('stm-template-aircrafts')) {
            $('.select2-container--default .select2-selection--single .select2-selection__arrow b', this.form).addClass('stm-preloader');
		}
	};

	SearchBox.prototype.success = function (data) {
		if(!this.listing) {
			/*Disable options*/
			if (typeof data.options != 'undefined') {
				$.each(data.options, function (key, options) {
					$('select[name=' + key + '] > option', this.form).each(function () {
						var slug = $(this).val();
						if (options.hasOwnProperty(slug)) {
							$(this).prop('disabled', options[slug].disabled);
						}
					});
				});
			}

			$('select:not(.hide)', this.form).select2("destroy").select2();

			if ($('body').hasClass('stm-template-motorcycle')) {
				$('.stm_mc-found .number-found', this.form).text(data.total);
			}
		} else {
            if($('body').hasClass('stm-template-aircrafts')) {
                $('.select2-container--default .select2-selection--single .select2-selection__arrow b', this.form).removeClass('stm-preloader');
            }
			$('button[type="submit"] span', this.form).text(data.total);
		}
	};

	SearchBox.prototype.complete = function () {
		if(!this.listing) {
			$('.select2-container--default .select2-selection--single .select2-selection__arrow b', this.form).removeClass('stm-preloader');
			$('select, button[type="submit"]', this.form).prop("disabled", false);
		}
	};

	STMListings.SearchBox = SearchBox;

	$.cookie.raw = true;

	var Compare = STMListings.Compare = function () {
		this.init();
		this.setCount();
		this.activateLinks();
		this.bind();
	};

	Compare.prototype.init = function () {
		var ids = this.ids = [];
		$.each($.cookie(), function (key, value) {
			if (key.match(/^compare_ids/)) {
				ids.push(value);
			}
		});
	};

	Compare.prototype.setCount = function () {
		$('[data-contains=compare-count]').text(this.ids.length ? this.ids.length : '');
	};

    Compare.prototype.click1 = function (e) {
		e.preventDefault();
		var target = $(e.target).closest('[data-id]');
		var dataId = target.attr('data-id');
		var dataAction = target.attr('data-action');

		if (typeof dataAction === 'undefined') {
			dataAction = 'add';
		}

		if (typeof dataId === 'undefined') {
			return;
		}

		$.ajax({
			url: ajaxurl,
			type: "POST",
			dataType: 'json',
			data: '&post_id=' + dataId + '&post_action=' + dataAction + '&action=stm_ajax_add_to_compare&security=' + addToCompare,
			context: this,
			beforeSend: function (data) {
				target.addClass('disabled');
			},
			success: function (data) {
				$('.single-add-to-compare').addClass('single-add-to-compare-visible');

				if (typeof data.response != 'undefined') {
					$('.single-add-to-compare .stm-title').text(data.response);
				}

				if (typeof data.length != 'undefined') {
					$('.stm-current-cars-in-compare').text(data.length);
				}

				setTimeout(function () {
					$('.single-add-to-compare').removeClass('single-add-to-compare-visible');
				}, 5000);

				if (data.status === 'danger') {
					return;
				}

				target.hide().siblings('[data-id]').show();
			},
			complete: function () {
				target.removeClass('disabled');
			}
		});
	};

	Compare.prototype.click2 = function (e) {
		e.preventDefault();
		var $el = $(e.currentTarget);
		var id = $el.data('id').toString();
		var stm_car_title = $el.data('title');

        if (this.ids.indexOf(id) === -1) {
			if (this.ids.length < 3) {
				$.cookie('compare_ids[' + id + ']', id, {expires: 7, path: '/'});
				this.ids.push(id);
				$el.addClass('active');

				//Added popup
				$('.single-add-to-compare .stm-title').text(stm_car_title + ' - ' + stm_added_to_compare_text);
				$('.single-add-to-compare').addClass('single-add-to-compare-visible');
				setTimeout(function () {
					$('.single-add-to-compare').removeClass('single-add-to-compare-visible');
				}, 5000);
				//Added popup
			} else {
				//Already added 3 popup
				$('.single-add-to-compare .stm-title').text(stm_already_added_to_compare_text);
				$('.single-add-to-compare').addClass('single-add-to-compare-visible');
				etTimeout(function () {
					$('.single-add-to-compare').removeClass('single-add-to-compare-visible');
					$('.single-add-to-compare').removeClass('overadded');
					$('.compare-remove-all').remove();
				}, 5000);
				//Already added 3 popup
				$('.single-add-to-compare').addClass('overadded');
				$('.compare-remove-all').remove();
				$('.single-add-to-compare .compare-fixed-link').before('<a href="#" class="compare-fixed-link compare-remove-all pull-right heading-font">' + resetAllTxt + '</a>');
			}
		} else {
			$.removeCookie('compare_ids[' + id + ']', {path: '/'});
			this.ids.splice(this.ids.indexOf(id), 1);
			$el.removeClass('active');

			//Deleted from compare text
			$('.single-add-to-compare .stm-title').text(stm_car_title + ' ' + stm_removed_from_compare_text);
			$('.single-add-to-compare').addClass('single-add-to-compare-visible');
			setTimeout(function () {
				$('.single-add-to-compare').removeClass('single-add-to-compare-visible');
			}, 5000);
			//Deleted from compare text

			$('.single-add-to-compare').removeClass('overadded');
			$('.compare-remove-all').remove();
		}

		this.setCount();
	};

	Compare.prototype.remove = function (e) {
		e.preventDefault();
		var $el = $(e.currentTarget);
		var dataId = $el.attr('data-id');
		var dataAction = $el.attr('data-action');
		if (typeof dataId === 'undefined') {
			return;
		}

		$.ajax({
			url: ajaxurl,
			type: "POST",
			dataType: 'json',
			data: '&post_id=' + dataId + '&post_action=' + dataAction + '&action=stm_ajax_add_to_compare&security=' + addToCompare,
			context: this,
			beforeSend: function (data) {
				$el.addClass('loading');

				if(parseFloat($('.stm-current-cars-in-compare').text()) > 0) {
					$('.stm-current-cars-in-compare').text(parseFloat($('.stm-current-cars-in-compare:first').text()) - 1);
				}

				$('.car-listing-row .compare-col-stm-' + dataId).hide('slide', {direction: 'left'}, function(){
					$('.car-listing-row .compare-col-stm-' + dataId).remove();
					$('.car-listing-row').append($('.compare-empty-car-top').html());
				});

				$('.stm-compare-row .compare-col-stm-' + dataId).hide('slide', {direction: 'left'}, function(){
					$('.stm-compare-row .compare-col-stm-' + dataId).remove();
					$('.stm-compare-row').append($('.compare-empty-car-bottom').html());
				});

				$('.row-compare-features .compare-col-stm-' + dataId).hide('slide', {direction: 'left'}, function(){
					$('.row-compare-features .compare-col-stm-' + dataId).remove();
					if($('.row-compare-features .col-md-3').length < 2) {
						$('.row-compare-features').slideUp();
					}
				});
			}
		});
	};

	Compare.prototype.removeAll = function (e) {
		e.preventDefault();

		$.each(this.ids, function (i, id) {
			$.removeCookie('compare_ids[' + id + ']', { path: '/' });
		});

		this.ids = [];
		this.activateLinks();
		this.setCount();
		location.reload();
	};

	Compare.prototype.activateLinks = function (ctx) {
		$.each(this.ids, function (i, value) {
			if (!value) {
				return;
			}

			$('[data-compare-id=' + value + ']', ctx).each(function () {
				$('a', this).eq(0).show();
				$('a', this).eq(1).hide();
			});

			$('.stm-compare-directory-new, .stm-listing-compare, .stm-gallery-action-unit.compare', ctx)
				.filter('[data-id=' + value + ']')
				.addClass('active')
				.tooltip('destroy')
				.attr('title', stm_i18n.remove_from_compare)
				.tooltip()
			;
		});
	};

	Compare.prototype.bind = function () {
		$(document).on('click', '.add-to-compare', $.proxy(this.click1, this));
		$(document).on('click', '.stm-user-public-listing .stm-listing-compare, .image .stm-listing-compare, .listing-list-loop.stm-listing-directory-list-loop .stm-listing-compare, .stm-gallery-action-unit.compare, .stm-compare-directory-new', $.proxy(this.click2, this));
		$(document).on('click', '.remove-from-compare', $.proxy(this.remove, this));
		$(document).on('click', '.compare-remove-all', $.proxy(this.removeAll, this));
	};


	var Favorites = STMListings.Favorites = function () {
		$('body.logged-in').on('click', '.stm-listing-favorite, .stm-listing-favorite-action', this.clickUser);
		$('body.stm-user-not-logged-in').on('click', '.stm-listing-favorite, .stm-listing-favorite-action', this.clickGuest);

		this.ids = $.cookie('stm_car_favourites');

		if (this.ids) {
			this.ids = this.ids.split(',');
		}
		else {
			this.ids = [];
		}

		var _this = this;
		if ($('body').hasClass('logged-in')) {
			$.getJSON(ajaxurl, { action: 'stm_ajax_get_favourites', security: getFav }, function (data) {
				_this.ids = data;
				_this.activateLinks();
			});
		}
		else {
			this.activateLinks();
		}
	};

	Favorites.prototype.clickUser = function (e) {
		e.preventDefault();

		if ($(this).hasClass('disabled')) {
			return false;
		}

		$(this).toggleClass('active');
		var stm_car_add_to = $(this).data('id');

		$.ajax({
			url: ajaxurl,
			type: "POST",
			dataType: 'json',
			data: '&car_id=' + stm_car_add_to + '&action=stm_ajax_add_to_favourites&security=' + addToFav,
			context: this,
			beforeSend: function (data) {
				$(this).addClass('disabled');
			},
			success: function (data) {
				if (data.count) {
					$('.stm-my-favourites span').text(data.count);
				}
				$(this).removeClass('disabled');
			}
		});
	};

	Favorites.prototype.clickGuest = function (e) {
		e.preventDefault();

		$(this).toggleClass('active');

		var stm_cookies = $.cookie();
		var stm_car_add_to = $(this).data('id');
		var stm_car_favourites = [];

		if (typeof(stm_cookies['stm_car_favourites']) !== 'undefined') {
			stm_car_favourites = stm_cookies['stm_car_favourites'].split(',');
			var index = stm_car_favourites.indexOf(stm_car_add_to.toString());
			if (index !== -1) {
				stm_car_favourites.splice(index, 1);
			} else {
				stm_car_favourites.push(stm_car_add_to.toString());
			}

			stm_car_favourites = stm_car_favourites.join(',');
			$.cookie('stm_car_favourites', stm_car_favourites, { expires: 7, path: '/' });

		} else {
			$.cookie('stm_car_favourites', stm_car_add_to.toString(), { expires: 7, path: '/' });
		}
	};

	Favorites.prototype.activateLinks = function (ctx) {
		$.each(this.ids, function (key, value) {
			if (!value) {
				return;
			}

			$('.stm-listing-favorite, .stm-listing-favorite-action', ctx)
				.filter('[data-id=' + value + ']')
				.addClass('active')
				.tooltip('destroy')
				.attr('title', stm_i18n.remove_from_favorites)
				.tooltip({placement: 'auto'})
			;
		});
	};


	var stm_single_filter_link = false;
    var stmIsotope;
    var $container = $('.stm-isotope-sorting');

    $(document).ready(function () {
        /*Terms and conditions rental*/
        $(document).on("click", '.stm-template-car_rental .form-row.terms a', function(event){
            event.preventDefault();

        });

        /*SHOW NOTIFICATION POPUP IF CAR NOT IN STOCK IN SELECTED OFFICE (STEP 1 RESERVATION)*/
        if($('body').hasClass('stm-template-rental-daypicker-page')) {
            $('select[name="pickup_location"]').on('change', function () {
                var locationId = $(this).select2("val");

                $.ajax({
                    url: ajaxurl,
                    type: "GET",
                    dataType: 'json',
                    context: this,
                    data: 'rental_office_id=' + locationId + '&action=stm_ajax_rental_check_car_in_current_office&security=' + rentalCheckCar,
                    success: function (data) {
                        $("#select-vehicle-popup").attr("href", $("#select-vehicle-popup").attr('href').split("?")[0] + "?pickup_location=" + locationId);
                        if (data == 'EMPTY') {
                            $('.choose-another-class').addClass('single-add-to-compare-visible');
                            setTimeout(function(){
                                $('.choose-another-class').removeClass('single-add-to-compare-visible');
                            }, 5000);
                        }
                    }
                });
            });


        }

        stm_ajax_filter_remove_hidden();
        stm_ajax_add_test_drive();
        stm_ajax_get_car_price();
        stm_ajax_add_trade_offer();
        clearFilter();
        stm_filter_links();

        stm_favourites();

        loadMoreDealerCars();
        loadMoreDealerReviews();

        //Isotope
        stm_sort_listing_cars();
        stm_modern_filter_isotope();

        //Clear select value from listing badge
        $('body').on('click', '#modern-filter-listing .stm-clear-listing-one-unit', function(e){
            var selectToClear = $(this).data('select');

            if(typeof selectToClear != 'undefined') {
                if (selectToClear != 'price' && $('select[name=' + selectToClear + ']').length > 0) {
                    $('select[name=' + selectToClear + ']').val('').change();

                    var $priceRange = $('#stm-' + selectToClear + '-range');
                    if ($priceRange.length > 0) {
                        $priceRange.slider('values', [stmOptionsObj.min, stmOptionsObj.max]);
                        $("#stm_filter_min_" + selectToClear).val(stmOptionsObj.min);
                        $("#stm_filter_max_" + selectToClear).val(stmOptionsObj.max);
                    }


                } /*else if() {

                } */else {
                    var $priceRange = $('#stm-' + selectToClear + '-range');
                    if ($priceRange.length > 0) {
                        $priceRange.slider('values', [stmOptions.min, stmOptions.max]);
                        $("#stm_filter_min_" + selectToClear).val(stmOptions.min);
                        $("#stm_filter_max_" + selectToClear).val(stmOptions.max);
                    }
                }
            }

        });

        $('body').on('click', '.stm-clear-listing-one-unit-classic', function(){
            var selectToClear = $(this).data('select');
            var clearLinkArg = $(this).data('url');
            if(typeof selectToClear != 'undefined') {
                if(selectToClear.indexOf('[')){
                    window.location = clearLinkArg;
                    return false;
                }
                if (selectToClear != 'price' && $('select[name=' + selectToClear + ']').length > 0) {
                    window.location = clearLinkArg;
                } else {
                    var $priceRange = $('#stm-price-range');
                    if ($priceRange.length > 0) {
                        $priceRange.slider('values', [stmOptions.min, stmOptions.max]);
                        $("#stm_filter_min_price").val(stmOptions.min);
                        $("#stm_filter_max_price").val(stmOptions.max);
                        $('.classic-filter-row form').submit();
                    }
                }
            }
        });

        $('body').on('click', '#lmb-car-review', function (e) {
            e.preventDefault();
            stm_load_cars_with_review();
        });

        $('body').on('click', '');
    });

    function stm_ajax_filter_remove_hidden() {
	    if($('body').hasClass('stm-template-listing') || $('body').hasClass('stm-template-aircrafts')) {
            $('.filter-listing.stm-vc-ajax-filter form').each(function () {
                new SearchBox(this);
            });
        } else {
            $('.filter.stm-vc-ajax-filter select:not(.hide)').select2().on('change', function(){
                $('input[name="offset"]').val(0);
                $('.load-more-btn-wrap').hide();
                var $_form = $(this).closest('form');
                var stmUrl = $_form.attr('action');

                $.ajax({
                    url: stmUrl,
                    dataType: 'json',
                    context: this,
                    data: $_form.serialize() + '&ajax_action=listings-binding&security=' + filterRemoveHidden,
                    beforeSend: function(){
                        if(typeof filterApp != 'undefined') filterApp.loadListings = true;
                        if(!$('body').hasClass('stm-template-listing')) {
                            $('.filter.stm-vc-ajax-filter select, .filter.stm-vc-ajax-filter button[type="submit"]').prop("disabled", true);
                            $('.select2-container--default .select2-selection--single .select2-selection__arrow b').addClass('stm-preloader');
                        }
                    },
                    success: function (data) {

                        if(!$('body').hasClass('stm-template-listing')) {
                            $('.select2-container--default .select2-selection--single .select2-selection__arrow b').removeClass('stm-preloader');
                            $('.filter.stm-vc-ajax-filter select, .filter.stm-vc-ajax-filter button[type="submit"]').prop("disabled", false);

                            /*Disable options*/
                            if (typeof data.options != 'undefined') {
                                $.each(data.options, function (key, options) {
                                    $('select[name=' + key + '] > option', $_form).each(function () {
                                        var slug = $(this).val();
                                        if (options.hasOwnProperty(slug)) {
                                            $(this).prop('disabled', options[slug].disabled);
                                        }
                                    });
                                });
                            }

                            if(typeof data.offset != 'undefined') {
                                $('input[name="offset"]').val(data.offset);
                                $('.load-more-btn-wrap').show();
                            } else {
                            }


                            if (typeof data.posts != 'undefined') {

                                if(typeof data.fp != 'undefined') {
                                    $('.selected-filter').text(data.fp);
                                    if(!$('.c-r-remove-filter').hasClass('active')) $('.c-r-remove-filter').addClass('active');
                                    if(data.fp == "") $('.c-r-remove-filter').removeClass('active');
                                } else {
                                    $('.selected-filter').text('');
                                    $('.c-r-remove-filter').removeClass('active');
                                }

                                filterApp.posts = data.posts;
                                setTimeout(function () {
                                    filterApp.loadListings = false;
                                }, 300);
                            }

                            $('.filter.stm-vc-ajax-filter select:not(.hide)').select2("destroy");
                            $('.filter.stm-vc-ajax-filter select:not(.hide)').select2();

                            if($('body').hasClass('stm-template-motorcycle')) {
                                $('.stm_mc-found .number-found').text(data.total);
                            }
                        }

						$_form.find('button[type="submit"] span').text(data.total);
                    }
                });
                return false;
            });
        }
    }

    function stm_favourites() {
    	window.stm_favourites = new Favorites();
    	window.stm_compare = new Compare();
    }

    function stm_ajax_add_test_drive() {
        $('#test-drive form').on("submit", function(event){
            event.preventDefault();
            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                context: this,
                data: $( this ).serialize() + '&action=stm_ajax_add_test_drive&security=' + addTestDrive,
                beforeSend: function(){
                    $('.alert-modal').remove();
                    $(this).closest('form').find('input').removeClass('form-error');
					$(this).closest('form').find('.stm-ajax-loader').addClass('loading');
                },
                success: function (data) {
					$(this).closest('form').find('.stm-ajax-loader').removeClass('loading');
                    $(this).closest('form').find('.modal-body').append('<div class="alert-modal alert alert-'+ data.status +' text-left">' + data.response + '</div>')
                    for(var key in data.errors) {
                        $('#request-test-drive-form input[name="' + key + '"]').addClass('form-error');
                    }
                }
            });
            $(this).closest('form').find('.form-error').on('hover', function () {
                $(this).removeClass('form-error');
            });
        });
    }

    function stm_ajax_add_trade_offer() {
        $('#trade-offer form').on("submit", function(event){
            event.preventDefault();
            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                context: this,
                data: $( this ).serialize() + '&action=stm_ajax_add_trade_offer&security=' + addTradeOffer,
                beforeSend: function(){
                    $('.alert-modal').remove();
                    $(this).closest('form').find('input').removeClass('form-error');
                    $(this).closest('form').find('.stm-ajax-loader').addClass('loading');
                },
                success: function (data) {
                    $(this).closest('form').find('.stm-ajax-loader').removeClass('loading');
                    $(this).closest('form').find('.modal-body').append('<div class="alert-modal alert alert-'+ data.status +' text-left">' + data.response + '</div>')
                    for(var key in data.errors) {
                        $('#request-trade-offer-form input[name="' + key + '"]').addClass('form-error');
                    }
                }
            });
            $(this).closest('form').find('.form-error').on('hover', function () {
                $(this).removeClass('form-error');
            });
        });
    }

    function stm_ajax_get_car_price() {
        $('#get-car-price form').on("submit", function(event){
            event.preventDefault();
            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                context: this,
                data: $( this ).serialize() + '&action=stm_ajax_get_car_price&security=' + getCarPrice,
                beforeSend: function(){
                    $('.alert-modal').remove();
                    $(this).closest('form').find('input').removeClass('form-error');
                    $(this).closest('form').find('.stm-ajax-loader').addClass('loading');
                },
                success: function (data) {
                    $(this).closest('form').find('.stm-ajax-loader').removeClass('loading');
                    $(this).closest('form').find('.modal-body').append('<div class="alert-modal alert alert-'+ data.status +' text-left">' + data.response + '</div>')
                    for(var key in data.errors) {
                        $('#get-car-price input[name="' + key + '"]').addClass('form-error');
                    }
                }
            });
            $(this).closest('form').find('.form-error').on('hover', function () {
                $(this).removeClass('form-error');
            });
        });
    }

    function clearFilter() {
        $('.reset-all').on('click', function(e){
        	e.preventDefault();

        	$(this).closest('.filter').find('select').trigger('reset');

			$(this).closest('.filter').find('select').find('option').each(function(){
				if( $(this).attr('data-disabled') == 'disabled')
					$(this).attr('disabled','disabled')
			})

            if($('.filter-price').length > 0) {
                $('.stm-price-range').slider( 'values', [ stmOptions.min, stmOptions.max ] );
                $("#stm_filter_min_price").val(stmOptions.min);
                $("#stm_filter_max_price").val(stmOptions.max);
            }
        });
    }

    function stm_sort_listing_cars() {
        // init Isotope
        if($('#modern-filter-listing .stm-isotope-sorting').length) {
            if (typeof imagesLoaded == 'function') {
                $('.stm-isotope-sorting').imagesLoaded(function() {
                    stmIsotope = $container.isotope({
                        itemSelector: '.stm-isotope-listing-item',
                        layoutMode: 'fitRows',
                        hiddenStyle: {
                            opacity: 0
                        },
                        visibleStyle: {
                            opacity: 1
                        },
                        transitionDuration: '0.5s',
                        getSortData: {
                            price: function (itemElem) {
                                var price = $(itemElem).data('price');
                                return parseFloat(price);
                            },
                            date: function (itemElem) {
                                var date = $(itemElem).data('date');
                                return parseFloat(date);
                            },
                            mileage: function (itemElem) {
                                var mileage = $(itemElem).data('mileage');
                                return parseFloat(mileage);
                            }
                        }
                    });
                });
            }
            $('#modern-filter-listing .stm-select-sorting select').select2().on('change', function(){
                stm_isotope_sort_function($(this).select2('val'));
            })
        }
    }

    function stm_modern_filter_isotope() {

        if($('#modern-filter-listing').length > 0) {

            $('body').on('click', '.modern-filter-badges ul li i', function(){
                var tab_reset = $(this).data('select');

                if(tab_reset == 'price') {
                    $('.stm-price-range').slider( 'values', [ stmOptions.min, stmOptions.max ] );

                    $("#stm_filter_min_price").val(stmOptions.min);
                    $("#stm_filter_max_price").val(stmOptions.max);

                    stmIso.isotope({
                        filter: function () {
                            var itemPrice = $(this).data('price');

                            return parseInt(itemPrice, 10) >= stmOptions.min && parseInt(itemPrice, 10) <= stmOptions.max;
                        }
                    });

                    /*price_string = '<li><span>Price:</span> ' + stmOptions.min + ' - ' + stmOptions.max;
                    price_string += '<i class="fa fa-close stm-clear-listing-one-unit" data-select="price"></i></li>';*/

                    price_string = '';
                    $(this).closest('li').remove();
                    var badges_length = $(".stm-filter-chosen-units-list li").length;
                    if(badges_length < 1) $('.stm-filter-chosen-units-list').css({ height: 0 });
                } else if(typeof stmOptionsObj[tab_reset] !== 'undefined'){
                    $('.stm-' + tab_reset + '-range').slider( 'values', [ stmOptionsObj[tab_reset].min, stmOptionsObj[tab_reset].max ] );

                    $("#stm_filter_min_" + tab_reset).val(stmOptionsObj[tab_reset].min);
                    $("#stm_filter_max_" + tab_reset).val(stmOptionsObj[tab_reset].max);

                    stmIso.isotope({
                        filter: function () {
                            var itemPrice = $(this).data(tab_reset);
                            return parseInt(itemPrice, 10) >= stmOptionsObj[tab_reset].min && parseInt(itemPrice, 10) <= stmOptionsObj[tab_reset].max;
                        }
                    });

                    dynamic_string[tab_reset]= '';
                    $(this).closest('li').remove();
                    var badges_length = $(".stm-filter-chosen-units-list li").length;
                    if(badges_length < 1) $('.stm-filter-chosen-units-list').css({ height: 0 });
                } else {
                    $('#' + tab_reset + ' input[type=checkbox]').each(function () {
                        if ($(this).prop('checked')) {
                            $(this).trigger('click');
                        }
                    })
                }


                /*Number of filtered*/
                if(typeof stmIsotope != 'undefined') {
                    var stmIsoData = stmIsotope.data('isotope');
                    if(typeof stmIsoData != 'undefined') {
                        if(typeof stmIsoData.filteredItems != 'undefined') {
                            $('.stm-modern-filter-found-cars span').text(stmIsoData.filteredItems.length);
                        }
                    }
                }
            });

            var stmSortClasses = '';
            var main_string = '';
            var price_string = '';
            var dynamic_string = new Object();
            var dynamic_string_html = '';
            var string = '';
            var stmIso = $('.stm-isotope-sorting');
            var stmFilterGroups = {};
            var stmPriceMaxSelected, stmPriceMinSelected = '';
            var stmMilMaxSelected, stmMilMinSelected = '';

            /*Checkbox clicked, filter*/
            $('#modern-filter-listing input[type=checkbox]').on('click', function () {
                var badges = {};
                var badges_reset = {};
                stmFilterGroups = {};
                $(window).scroll();
                var stmFirst = 0;
                stmSortClasses = '';
                var numberOfCats = 0;

                $('#modern-filter-listing input[type=checkbox]').each(function () {
                    var stmChecked = $(this).prop('checked');
                    var stmCurrentClass = $(this).attr('name');
                    var stmBadgeValue = $(this).data('name');
                    var stmBadgeId = $(this).closest('.content').attr('id');


                    var stmFilterCurrentGroup = $(this).closest('.collapse').attr('id');

                    if (stmChecked) {
                        var tab = $(this).closest('.stm-accordion-single-unit').find('.title h5').text();
                        if(typeof badges[tab] == 'undefined') {
                            badges[tab] = [];
                        }
                        if(typeof badges_reset[tab] == 'undefined') {
                            badges_reset[tab] = '';
                        }
                        badges[tab].push(stmBadgeValue);
                        badges_reset[tab] = stmBadgeId;


                        if (stmFirst == 0) {
                            stmSortClasses += '.' + stmCurrentClass;
                        } else {
                            stmSortClasses += '.' + stmCurrentClass;
                        }
                        stmFirst++;

                        if(typeof(stmFilterGroups[stmFilterCurrentGroup]) == 'undefined') {
                            stmFilterGroups[stmFilterCurrentGroup] = [];
                        }
                        stmFilterGroups[stmFilterCurrentGroup].push(stmCurrentClass);


                    }
                    if (stmSortClasses == '') {
                        stmSortClasses = '.all';
                    }

                });


                if ($('.stm-isotope-sorting').length > 0 && stmSortClasses != '') {
                    var matches = [];

                    stmIso.isotope({
                        filter: function() {
                            matches = [];
                            var itemPrice = $(this).data('price');
                            var minPrice = $('#stm_filter_min_price').val();
                            var maxPrice = $('#stm_filter_max_price').val();

                            if(Object.keys(stmFilterGroups).length > 0) {
                                for (var key in stmFilterGroups) {
                                    if (stmFilterGroups.hasOwnProperty(key)) {
                                        for (var k = 0; k < stmFilterGroups[key].length; k++) {
                                            var match = false;
                                            if ($(this).hasClass(stmFilterGroups[key][k])) {
                                                matches[key] = true;
                                            }
                                        }
                                    }
                                }


                                var final_match = false;

                                if (Object.keys(matches).length == Object.keys(stmFilterGroups).length) {
                                    if (Object.keys(matches).length > 0) {
                                        for (var m_key in matches) {
                                            if (matches.hasOwnProperty(m_key)) {
                                                if (matches[m_key]) {
                                                    final_match = true;
                                                } else {
                                                    final_match = false;
                                                }
                                            }
                                        }
                                    } else {
                                        final_match = false;
                                    }
                                }


                                if (final_match) {
                                    if(typeof minPrice != 'undefined' && typeof maxPrice != 'undefined' && typeof itemPrice != 'undefined') {
                                        return parseInt(itemPrice, 10) >= minPrice && parseInt(itemPrice, 10) <= maxPrice;
                                    } else {
                                        return ($(this));
                                    }
                                }
                            } else {
                                return ($(this));
                            }

                        }
                    })
                }

                /*create badge*/
                string = '';

                for (var key in badges) {
                    if (badges.hasOwnProperty(key)) {
                        if(badges.hasOwnProperty(key)) {
                            string += '<li><span>' + key + ':</span> ' + badges[key].join(', ');
                            if(badges_reset.hasOwnProperty(key)) {
                                string += '<i class="fa fa-close stm-clear-listing-one-unit" data-select="' + badges_reset[key] + '"></i>';
                            }
                            string += '</li>';

                            main_string = get_dynamic_badge_html(dynamic_string) + price_string + string;
                            $('.modern-filter-badges ul.stm-filter-chosen-units-list').html(main_string);
                        }
                    }
                }

                if($.isEmptyObject(badges)){
                    main_string = get_dynamic_badge_html(dynamic_string) + price_string + string;
                    $('.modern-filter-badges ul.stm-filter-chosen-units-list').html(main_string);
                };

                //var badges_length = Object.keys(badges).length;
                var badges_length = $(".stm-filter-chosen-units-list li").length;
				if(badges_length > 0) {

					var badgesWidth = 0;
					var badgesMargin = 15;
					var badgesRowWidth = $('.stm-filter-chosen-units-list').outerWidth();

					$('.stm-filter-chosen-units-list li').each(function(){
						badgesWidth += $(this).outerWidth();
					});

					// Add margins
					badgesWidth += badgesMargin * (badges_length - 1);

					var row_number = (badgesWidth/badgesRowWidth) + 1;



					$('.stm-filter-chosen-units-list').css({
						height: (parseInt(row_number) * 47) + 'px'
					});
				} else {
					$('.stm-filter-chosen-units-list').css({
						height: 0
					});
				}

                /*Number of filtered*/
                if(typeof stmIsotope != 'undefined') {
                    var stmIsoData = stmIsotope.data('isotope');
                    if(typeof stmIsoData != 'undefined') {
                        if(typeof stmIsoData.filteredItems != 'undefined') {
                            $('.stm-modern-filter-found-cars span').text(stmIsoData.filteredItems.length);
                        }
                    }
                }
            });

            $(".stm-price-range").on('slide', function (event, ui) {

                var minPrice = ui.values[0];
                var maxPrice = ui.values[1];
                stmIso.isotope({
                    filter: function () {
                        	var matches = [];
                            var itemPrice = $(this).data('price');
                            var itemMil = (typeof ($(this).data('mileage')) != 'undefined') ? $(this).data('mileage') : 0;

                            if(Object.keys(stmFilterGroups).length > 0) {
                                for (var key in stmFilterGroups) {
                                    if (stmFilterGroups.hasOwnProperty(key)) {
                                        for (var k = 0; k < stmFilterGroups[key].length; k++) {
                                            var match = false;
                                            if ($(this).hasClass(stmFilterGroups[key][k])) {
                                                matches[key] = true;
                                            }
                                        }
                                    }
                                }


                                var final_match = false;

                                if (Object.keys(matches).length == Object.keys(stmFilterGroups).length) {
                                    if (Object.keys(matches).length > 0) {
                                        for (var m_key in matches) {
                                            if (matches.hasOwnProperty(m_key)) {
                                                if (matches[m_key]) {
                                                    final_match = true;
                                                } else {
                                                    final_match = false;
                                                }
                                            }
                                        }
                                    } else {
                                        final_match = false;
                                    }
                                }


                                if (final_match) {
                                    stmPriceMaxSelected = (typeof(maxPrice) == 'undefined') ? 0 : maxPrice;
                                    stmPriceMinSelected = (typeof(minPrice) == 'undefined') ? 0 : minPrice;

                                    if(stmMilMaxSelected !== '' && stmMilMinSelected !== '') {
                                        return parseInt(itemPrice, 10) >= minPrice && parseInt(itemPrice, 10) <= maxPrice && parseInt(itemMil, 10) >= stmMilMinSelected && parseInt(itemMil, 10) <= stmMilMaxSelected;
                                    } else {
                                        return parseInt(itemPrice, 10) >= minPrice && parseInt(itemPrice, 10) <= maxPrice;
                                    }
                                }
                            } else {
                                stmPriceMaxSelected = (typeof(maxPrice) == 'undefined') ? 0 : maxPrice;
                                stmPriceMinSelected = (typeof(minPrice) == 'undefined') ? 0 : minPrice;

                                if(stmMilMaxSelected !== '' && stmMilMinSelected !== '') {
                                    return parseInt(itemPrice, 10) >= minPrice && parseInt(itemPrice, 10) <= maxPrice && parseInt(itemMil, 10) >= stmMilMinSelected && parseInt(itemMil, 10) <= stmMilMaxSelected;
                                } else {
                                    return parseInt(itemPrice, 10) >= minPrice && parseInt(itemPrice, 10) <= maxPrice;
                                }
                            }
                    }
                });

                /*Number of filtered*/
                if(typeof stmIsotope != 'undefined') {
                    var stmIsoData = stmIsotope.data('isotope');
                    if(typeof stmIsoData != 'undefined') {
                        if(typeof stmIsoData.filteredItems != 'undefined') {
                            $('.stm-modern-filter-found-cars span').text(stmIsoData.filteredItems.length);
                        }
                    }
                }

                price_string = '<li><span>Price:</span> ' + minPrice + ' - ' + maxPrice;
                price_string += '<i class="fa fa-close stm-clear-listing-one-unit" data-select="price"></i></li>';
                main_string = get_dynamic_badge_html(dynamic_string) + price_string + string;
                $('.modern-filter-badges ul.stm-filter-chosen-units-list').html(main_string);
                $('.stm-filter-chosen-units-list').height('47');
            });

            $(".stm-modern-dynamic-slider").each(function () {
                var sliderIdent = $(this).data("slider-name");

                $(".stm-" + sliderIdent + "-range").on('slide', function (event, ui) {
                    var minMileage = ui.values[0];
                    var maxMileage = ui.values[1];
                    stmIso.isotope({
                        filter: function () {
                            var matches = [];
                            var itemPrice = (typeof ($(this).data('price')) != 'undefined') ? $(this).data('price') : 0;
                            var itemMileage = $(this).data(sliderIdent);

                            if(Object.keys(stmFilterGroups).length > 0) {
                                for (var key in stmFilterGroups) {
                                    if (stmFilterGroups.hasOwnProperty(key)) {
                                        for (var k = 0; k < stmFilterGroups[key].length; k++) {
                                            var match = false;
                                            if ($(this).hasClass(stmFilterGroups[key][k])) {
                                                matches[key] = true;
                                            }
                                        }
                                    }
                                }


                                var final_match = false;

                                if (Object.keys(matches).length == Object.keys(stmFilterGroups).length) {
                                    if (Object.keys(matches).length > 0) {
                                        for (var m_key in matches) {
                                            if (matches.hasOwnProperty(m_key)) {
                                                if (matches[m_key]) {
                                                    final_match = true;
                                                } else {
                                                    final_match = false;
                                                }
                                            }
                                        }
                                    } else {
                                        final_match = false;
                                    }
                                }


                                if (final_match) {
                                    stmMilMaxSelected = maxMileage;
                                    stmMilMinSelected = minMileage;

                                    if(stmPriceMaxSelected !== '' && stmPriceMinSelected !== '') {
                                        return parseInt(itemMileage, 10) >= minMileage && parseInt(itemMileage, 10) <= maxMileage && parseInt(itemPrice, 10) >= stmPriceMinSelected && parseInt(itemPrice, 10) <= stmPriceMaxSelected;
                                    } else {
                                        return parseInt(itemMileage, 10) >= minMileage && parseInt(itemMileage, 10) <= maxMileage;
                                    }
                                }
                            } else {
                                stmMilMaxSelected = maxMileage;
                                stmMilMinSelected = minMileage;

                                if(stmPriceMaxSelected !== '' && stmPriceMinSelected !== '') {
                                    return parseInt(itemMileage, 10) >= minMileage && parseInt(itemMileage, 10) <= maxMileage && parseInt(itemPrice, 10) >= stmPriceMinSelected && parseInt(itemPrice, 10) <= stmPriceMaxSelected;
                                } else {
                                    return parseInt(itemMileage, 10) >= minMileage && parseInt(itemMileage, 10) <= maxMileage;
                                }
                            }
                        }
                    });

                    /*Number of filtered*/
                    if(typeof stmIsotope != 'undefined') {
                        var stmIsoData = stmIsotope.data('isotope');
                        if(typeof stmIsoData != 'undefined') {
                            if(typeof stmIsoData.filteredItems != 'undefined') {
                                $('.stm-modern-filter-found-cars span').text(stmIsoData.filteredItems.length);
                            }
                        }
                    }

                    dynamic_string_html = '<li><span>' + sliderIdent + ':</span> ' + minMileage + ' - ' + maxMileage;
                    dynamic_string_html += '<i class="fa fa-close stm-clear-listing-one-unit" data-select="'+ sliderIdent +'"></i></li>';

                    dynamic_string[sliderIdent] = dynamic_string_html;

                    main_string = get_dynamic_badge_html(dynamic_string) + price_string + string;
                    $('.modern-filter-badges ul.stm-filter-chosen-units-list').html(main_string);
                    $('.stm-filter-chosen-units-list').height('47');
                });

            });
        }
    }

    function get_dynamic_badge_html(dynamic_string) {
        var dynamic_string_html = '';
        $.each(dynamic_string, function(key, event) {
            dynamic_string_html += event;
        });

        return dynamic_string_html;
    }

    function stm_filter_links() {
        $('body').on('click', '.stm-single-filter-link', function(){
            stm_single_filter_link = true;
            var stm_name = $(this).data('slug');
            var stm_value = $(this).data('value');
            if(typeof stm_name !== 'undefined' && typeof stm_value !== 'undefined') {
                $('.reset-all').trigger('click');
                $('#stm-filter-links-input').attr('name', stm_name);
                $('#stm-filter-links-input').val(stm_value);
            }
        })
    }

    STMListings.save_user_settings = function () {
        $('#stm_user_settings_edit.stm_save_user_settings_ajax').submit(function (e) {

            var formData = new FormData();

            /*Add image*/
            formData.append('stm-avatar', $('input[name="stm-avatar"]')[0].files[0]);

            /*Add text fields*/
            var formInputs = $(this).serializeArray();

            for (var key in formInputs) {
                if (formInputs.hasOwnProperty(key)) {
                    formData.append(formInputs[key]['name'], formInputs[key]['value']);
                }
            }

            formData.append('action', 'stm_listings_ajax_save_user_data');

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('.stm-user-message').empty();
                    $(this).find('.stm-listing-loader').addClass('visible');
                },
                success: STMListings.save_user_settings_success
            });
        })
    };

    STMListings.save_user_settings_success = function (data) {
        $(this).find('.stm-listing-loader').removeClass('visible');
        $('.stm-user-message').text(data.error_msg);

        $('.stm-image-avatar img, .stm-dropdown-user-small-avatar img').attr('src', data.new_avatar);

		if (typeof data.new_avatar === 'undefined' || data.new_avatar === '') {
			$('.stm-image-avatar').removeClass('hide-empty').addClass('hide-photo');
		} else {
			$('.stm-image-avatar').addClass('hide-empty').removeClass('hide-photo');
		}

    };

	STMListings.stm_logout = function() {
        $('body').on('click', '.stm_logout a', function(e){
            e.preventDefault();
            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                context: this,
                data: {
                    'action':'stm_logout_user'
                },
                beforeSend: function(){
                    $('.stm_add_car_form .stm-form-checking-user .stm-form-inner').addClass('activated');
                },
                success: function (data) {
                    if(data.exit) {
                        $('#stm_user_info').slideUp('fast',function(){
                            $(this).empty();
                            $('.stm-not-enabled, .stm-not-disabled').slideDown('fast');
                            $("html, body").animate({ scrollTop: $('.stm-form-checking-user').offset().top }, "slow");
                        });

                        $('.stm-form-checking-user button[type="submit"]').removeClass('enabled').addClass('disabled');
                    }
                    $('.stm_add_car_form .stm-form-checking-user .stm-form-inner').removeClass('activated');
                }
            });
        })
    };

	STMListings.stm_ajax_login = function() {
        $('.lOffer-account-unit').mouseout(function(){
            $('.stm-login-form-unregistered').removeClass('working');
        });
        $('.stm-forgot-password a').on('click', function(e){
            e.preventDefault();
            $('.stm_forgot_password_send').slideToggle();
            $('.stm_forgot_password_send input[type=text]').focus();
            $(this).toggleClass('active');
        })

        $(".stm-login-form-mobile-unregistered form,.stm-login-form form:not(.stm_password_recovery), .stm-login-form-unregistered form").submit(function (e) {
            e.preventDefault();
            if(!$(this).hasClass('stm_forgot_password_send')) {
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    dataType: 'json',
                    context: this,
                    data: $(this).serialize() + '&action=stm_custom_login',
                    beforeSend: function () {
                        $(this).find('input').removeClass('form-error');
                        $(this).find('.stm-listing-loader').addClass('visible');
                        $('.stm-validation-message').empty();

                        if ($(this).parent('.lOffer-account-unit').length > 0) {
                            $('.stm-login-form-unregistered').addClass('working');
                        }
                    },
                    success: function (data) {
                        if ($(this).parent('.lOffer-account-unit').length > 0) {
                            $('.stm-login-form-unregistered').addClass('working');
                        }
                        if (data.user_html) {
                            var $user_html = $(data.user_html).appendTo('#stm_user_info');
                            $('.stm-not-disabled, .stm-not-enabled').slideUp('fast', function () {
                                $('#stm_user_info').slideDown('fast');
                            });

                            $("html, body").animate({scrollTop: $('.stm-form-checking-user').offset().top}, "slow");
                            $('.stm-add-a-car-login-overlay,.stm-add-a-car-login').toggleClass('visiblity');

                            $('.stm-form-checking-user button[type="submit"]').removeClass('disabled').addClass('enabled');
                        }

                        if(data.restricted && data.restricted) {
                            $('.btn-add-edit').remove();
                        }

                        $(this).find('.stm-listing-loader').removeClass('visible');
                        for (var err in data.errors) {
                            $(this).find('input[name=' + err + ']').addClass('form-error');
                        }

                        if (data.message) {
                            var message = $('<div class="stm-message-ajax-validation heading-font">' + data.message + '</div>').hide();

                            $(this).find('.stm-validation-message').append(message);
                            message.slideDown('fast');
                        }


                        if (typeof(data.redirect_url) !== 'undefined') {
                            window.location = data.redirect_url;
                        }
                    }
                });
            } else {
                /*Send passs*/
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    dataType: 'json',
                    context: this,
                    data: $(this).serialize() + '&action=stm_restore_password&security=' + restorePassword,
                    beforeSend: function () {
                        $(this).find('input').removeClass('form-error');
                        $(this).find('.stm-listing-loader').addClass('visible');
                        $('.stm-validation-message').empty();
                    },
                    success: function (data) {
                        $(this).find('.stm-listing-loader').removeClass('visible');
                        if (data.message) {
                            var message = $('<div class="stm-message-ajax-validation heading-font">' + data.message + '</div>').hide();

                            $(this).find('.stm-validation-message').append(message);
                            message.slideDown('fast');
                        }
                        for (var err in data.errors) {
                            $(this).find('input[name=' + err + ']').addClass('form-error');
                        }
                    }
                });
            }
        });

        $('.user_validated_field').on('hover', function(){
            $(this).removeClass('form-error');
        });

        $('input[name="stm_accept_terms"]').on('click', function(){
            if($(this).is(':checked')) {
                $('.stm-login-register-form .stm-register-form form input[type="submit"]').removeAttr('disabled');
            } else {
                $('.stm-login-register-form .stm-register-form form input[type="submit"]').attr('disabled', '1');
            }
        });

    };

    function loadMoreDealerCars() {
        $('body').on('click', '.stm-load-more-dealer-cars a', function(e){
            e.preventDefault();

            if($(this).closest('.stm-load-more-dealer-cars').hasClass('not-clickable')) {
                return false;
            }

            var offset = $(this).attr('data-offset');
            var user_id = $(this).data('user');
            var popular = $(this).data('popular');
            var view_type = $('#stm_dealer_view_type').val();

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'stm_ajax_dealer_load_cars',
                    offset: offset,
                    user_id: user_id,
                    popular: popular,
                    view_type: view_type,
					security: dealerLoadCars
                },
                method: 'POST',
                dataType: 'json',
                context: this,
                beforeSend: function(){
                    $(this).closest('.stm-load-more-dealer-cars').addClass('not-clickable');
                },
                success: function (data) {
                    $(this).closest('.stm-load-more-dealer-cars').removeClass('not-clickable');
                    if(data.html) {
                        $(this).closest('.tab-pane').find('.car-listing-row').append(data.html);
                    }
                    if(data.new_offset) {
                        $(this).attr('data-offset', data.new_offset);
                    }
                    if(data.button == 'hide') {
                        $(this).closest('.stm-load-more-dealer-cars').slideUp();
                        $(this).closest('.tab-pane').find('.row-no-border-last').removeClass('row-no-border-last');
                    }

                    $('img.lazy').lazyload({
                        effect: "fadeIn",
                        failure_limit: Math.max('img'.length - 1, 0)
                    });
                }
            });
        })
    }

    function loadMoreDealerReviews() {
        $('body').on('click', '.stm-load-more-dealer-reviews a', function(e){
            e.preventDefault();

            if($(this).closest('.stm-load-more-dealer-reviews').hasClass('not-clickable')) {
                return false;
            }

            var offset = $(this).attr('data-offset');
            var user_id = $(this).data('user');

            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'stm_ajax_dealer_load_reviews',
                    offset: offset,
                    user_id: user_id,
					security: dealerLoadReviews
                },
                method: 'POST',
                dataType: 'json',
                context: this,
                beforeSend: function(){
                    $(this).closest('.stm-load-more-dealer-reviews').addClass('not-clickable');
                },
                success: function (data) {
                    $(this).closest('.stm-load-more-dealer-reviews').removeClass('not-clickable');
                    if(data.html) {
                        $(this).closest('.tab-pane').find('#stm-dealer-reviews-units').append(data.html);
                    }
                    if(data.new_offset) {
                        $(this).attr('data-offset', data.new_offset);
                    }
                    if(data.button == 'hide') {
                        $(this).closest('.stm-load-more-dealer-reviews').slideUp();
                    }
                }
            });
        })
    }

    $('#stm_submit_review .button').on('click', function(e){
        e.preventDefault();

        if(!$(this).hasClass('disabled')) {

            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: $(this).closest('form').serialize() + '&action=stm_submit_review&security=' + submitReview,
                beforeSend: function () {
                    $(this).closest('form').find('.stm-icon-load1').addClass('activated');
                    $('#write-review-message ').slideUp();
                },
                success: function (data) {
                    $(this).closest('form').find('.stm-icon-load1').removeClass('activated');

                    if (data.message) {
                        $('#write-review-message ').text(data.message).slideDown();
                    }

                    if(data.updated) {
                        window.location.href = data.updated;
                        location.reload();
                    }
                }
            });
        }
    })

    $('.stm-comment-dealer-wrapper .stm-bottom .stm-report-review a').on('click', function(e){
        e.preventDefault();

        if(!$(this).hasClass('reported')) {
            var stmID = $(this).data('id');
            $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'id': stmID,
                    'action': 'stm_report_review',
					'security': reportReview
                },
                beforeSend: function () {
                    $(this).addClass('reported');
                },
                success: function (data) {
                    if (data.message) {
                        $(this).text(data.message);
                    }
                }
            });
        }
    });

    $('.dealer-search-title select').on('change', function(){
        $('input[name="stm_sort_by"]').val($(this).val());
        $("#stm_all_listing_tab").find("form").submit();
    });

    $('#stm_all_listing_tab_without_tabs .reset-filter').on('click', function (e) {
		e.preventDefault();
        $(this)
            .closest('form')
            .find('select')
            .val(null)
            .trigger('change.select2');

        $(this)
            .closest('form')
            .find('input')
            .val('');

		return false;
    });

    $('.stm-load-more-dealers').on('click', function(e){
        e.preventDefault();

        if($(this).hasClass('not-clickable')) {
            return false;
        }

        var offset = $(this).attr('data-offset');

        $.ajax({
            //url: ajaxurl,
            url: currentAjaxUrl,
            dataType: 'json',
            context: this,
            data: $('.stm_dynamic_listing_dealer_filter form').serialize() + '&offset=' + offset + '&ajax_action=stm_load_dealers_list&security=' + loadDealersList,
            beforeSend: function(){
               $(this).addClass('not-clickable');
            },
            success: function (data) {
                $(this).removeClass('not-clickable');
                if(data.user_html) {
                    $('.dealer-search-results table tbody').append(data.user_html);
                }
                if(data.new_offset) {
                    $('.stm-load-more-dealers').attr('data-offset', data.new_offset);
                }
                if(data.remove && data.remove === 'hide') {
                    $(this).remove();
                }
            }
        });
    });

    $('body').on("click", '.stm-show-number', function () {
        var parent = $(this).parent();
        var phone_owner_id = $(this).attr("data-id");
        parent.find(".stm-show-number").text('').addClass('load_number');
        $.ajax({
            url: ajaxurl,
            type: "GET",
            dataType: 'json',
            context: this,
            data: 'phone_owner_id=' + phone_owner_id + '&action=stm_ajax_get_seller_phone&security=' + sellerPhone,
            success: function (data) {
                parent.find(".stm-show-number").hide();
                parent.find(".phone").html('<a href="tel:' + data + '">' + data + '</a>');
            }
        });
    });

})(jQuery);

function stm_loadMoreCars(that,category,taxonomy,offset,per_page) {
    var $ = jQuery;
    $.ajax({
        url: ajaxurl,
        data: { action: 'stm_ajax_load_more_cars',category:category,taxonomy:taxonomy,offset: offset,per_page: per_page, security:loadMoreCars },
        method: 'POST',
        dataType: 'json',
        beforeSend: function(){
            $(that).addClass('not-visible');
            $(that).closest('.dp-in').find('.preloader').fadeIn(600);
        },
        success: function (data) {
            $(that).closest('.dp-in').find('.preloader').hide();
            if(data['content'] && data['appendTo']) {
                $(data['appendTo'] + ' .car-listing-row').append(data['content']);
            }
            if( data['button'] ){
                $(that).attr('onclick', data['button']).removeClass('not-visible');
            }else{
                $(data['appendTo']).find('.car-listing-actions').addClass('all-done');
                that.parent().text('');
            }
        }
    });
}

function stm_load_cars_with_review(upd) {
    var update = (upd) ? upd : false;
    var $ = jQuery;
    var $_form = $('#listing-with-review').closest('form');
    var stmUrl = $_form.attr('action');

    $.ajax({
        url: stmUrl,
        dataType: 'json',
        context: this,
        data: $_form.serialize() + '&ajax_action=listings-binding',
        beforeSend: function() {
            if(typeof filterApp != 'undefined') filterApp.loadListings = true;
        },
        success: function (data) {
            if(typeof data.offset != 'undefined') {
                $('input[name="offset"]').val(data.offset);
                $('.load-more-btn-wrap').show();
            } else {
                $('input[name="offset"]').val(0);
                $('.load-more-btn-wrap').hide();
            }

            if (typeof data.posts != 'undefined') {
                if(!update) {
                    for (var q = 0; q < data.posts.length; q++) {
                        filterApp.pushNewData(data.posts[q]);
                    }
                } else {
                    filterApp.posts = data.posts;
                    setTimeout(function () {
                        filterApp.loadListings = false;
                    }, 300);
                }
            }
        }
    });
    return false;
}