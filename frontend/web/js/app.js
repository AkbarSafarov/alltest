// Body Lock
let unlock = true;
let delay = 100;

function body_lock(delay) {
	let body = document.querySelector("body");
	if (body.classList.contains('_lock')) {
		body_lock_remove(delay);
	} else {
		body_lock_add(delay);
	}
}
function body_lock_remove(delay) {
	let body = document.querySelector("body");
	if (unlock) {
		let lock_padding = document.querySelectorAll("._lp");
		setTimeout(() => {
			for (let index = 0; index < lock_padding.length; index++) {
				const el = lock_padding[index];
				el.style.paddingRight = '0px';
			}
			body.style.paddingRight = '0px';
			body.classList.remove("_lock");
		}, delay);

		unlock = false;
		setTimeout(function () {
			unlock = true;
		}, delay);
	}
}
function body_lock_add(delay) {
	let body = document.querySelector("body");
	if (unlock) {
		let lock_padding = document.querySelectorAll("._lp");
		for (let index = 0; index < lock_padding.length; index++) {
			const el = lock_padding[index];
			el.style.paddingRight = window.innerWidth - document.querySelector('.wrapper').offsetWidth + 'px';
		}
		body.style.paddingRight = window.innerWidth - document.querySelector('.wrapper').offsetWidth + 'px';
		body.classList.add("_lock");

		unlock = false;
		setTimeout(function () {
			unlock = true;
		}, delay);
	}
}
//variables
let headerNotificationContent = $('.header-notification__list'),

    navMenuResizeBtn = $('.nav-menu__btn-resize'),

    navMenu = $('.nav-menu'),

    navOverlay = $('.nav-overlay'),

    passwordInputBtn = $('.input-item__password-btn'),

    inputPassword = $('.input-item__password input'),

    inputNum = $('.input-num'),

    verticalMenuBtnResize = $('.vertical-menu__btn'),

    headerNotificationTitle = $('.header-notification__title'),

    headerMenuMobile = $('.header-mobile-menu');



// Resize Course Menu
navMenuResizeBtn.on('click', function () {


    if (window.innerWidth < 992) {

        navMenu.removeClass('_resize');

        navOverlay.removeClass('_active');

        body_lock_remove(delay);

    } else {

        navMenuResizeBtn.toggleClass('_active');

        navMenu.toggleClass('_active');

    }

});


// Show and hide input password for click on button
passwordInputBtn.click(function () {

    passwordInputBtn.toggleClass("_active");

    if (inputPassword.attr("type") == "password") {

        inputPassword.attr("type", "text");

    } else {

        inputPassword.attr("type", "password");

    }

});


// Show and hide Nav menu for click on Vertical menu button
verticalMenuBtnResize.on('click', function () {

    if (window.innerWidth < 992) {

        body_lock_add(delay);

        navMenu.addClass('_resize');

        navOverlay.addClass('_active');

    } else {

        verticalMenuBtnResize.toggleClass('_active');

        navMenu.toggleClass('_resize');

    }

});


// click on Nav Overlay to close modal Nav menu
navOverlay.on('click', function () {

    navOverlay.removeClass('_active');

    navMenu.removeClass('_resize');

    body_lock_remove(delay);

});


// Allows to use only numbers in input
inputNum.on('input', function (e) {

    this.value = this.value.replace(/[^0-9 +\.]/g, '');

});


// function to close Notification modal
function removeHeaderNotificationModal(name) {

    name.on('click', function () {

        $('.header-notification').removeClass('_active');

        if (!headerMenuMobile.hasClass('_active')) {

            body_lock_remove(delay);

        }

    });

}


function headerNotificationAttr() {

    if (window.innerWidth < 768) {

        headerNotificationTitle.addClass('_active');

        removeHeaderNotificationModal(headerNotificationTitle);

        removeHeaderNotificationModal($('.header-notification__btn-cross'));

        $('.header-notification__btn').on('click', function () {

            if (!headerMenuMobile.hasClass('_active')) {

                body_lock_add(delay);

            }

        });

    } else {

        headerNotificationTitle.removeClass('_active');

    }

}


let educationBtn = $('.education__title');


$(window).on('load resize', function () {

    educationBtn.off('click')

    initAccordion()

})


function initAccordion() {

    if (window.innerWidth < 576) {

        educationBtn.on("click", function () {

            let thisBtn = $(this);

            let dropdown = thisBtn.next('.education__text')

            let educationDropdown = $('.education__text').not('.active');


            educationDropdown.slideUp(200)

            if (thisBtn.hasClass('active')) {

                educationBtn.removeClass('active')

                thisBtn.removeClass('active');

                dropdown.slideUp(200);

            }

            else {

                educationBtn.removeClass('active')

                thisBtn.addClass('active');

                dropdown.slideDown(200);

            }

        })

    }
}



let educationTitle = $('.reviews__item-title'),

    educationText = $('.reviews__item-text');


function educationOffSelect() {

    if (window.innerWidth > 576) {

        educationTitle.addClass('swiper-no-swiping');
        educationText.addClass('swiper-no-swiping');

    } else {

        educationTitle.removeClass('swiper-no-swiping');
        educationText.removeClass('swiper-no-swiping');

    }
};


$(window).on('load resize', function () {

    educationOffSelect()

});


// shows or hides the counter in the header
function headerNavNum(name) {

    if (name.text() > 0) {

        name.addClass('_active');

    } else {

        name.removeClass('_active');

    }

}


// test sortable
$(".sortable").sortable({
    placeholder: 'sortable-placeholder',
    start: function (event, ui) {
        ui.placeholder.height(ui.helper.outerHeight());

        $('.vertical-menu').removeClass('_active');
    }
});


//show/hide account password form
$('.account__reset-btn--update').on('click', function () {

    $(this).toggleClass('_active');

    $('.account__reset-form').slideToggle(500);

});


$('table').each(function () {
    $(this).wrap('<div style="overflow-x:auto;"></div>');
});

for (const el of document.querySelectorAll('video')) {
    el.addEventListener("contextmenu", function (e) {
        e.preventDefault();
    });
}
class AccordionMenu {

    constructor(selector, config) {

        //Should be ID or ClassName
        this.selector = selector;

        this.config = {};

        this.init(config);

    }

    init(config) {

        if (this.isElementExists(this.selector)) {


            //Applying config
            this.applyConfig(config);

            //Binding click event on toggling submenus
            this.clickEventBind(

                $(`${this.selector} .submenu ${this.config.triggerElement}`),

                this.toggleSubmenus,
                {
                    'oneTabMode': this.config.oneTabMode,
                    'toggleDataConfig': this.getToggleAccordionConfig('close'),
                    'submenu': this.config.openingElement,
                    'speed': this.config.toggleSpeed,
                    'activeClassTrigger': this.config.activeClassTrigger,
                    'activeClass': this.config.activeClass

                });

        } else {

            return false;

        }

    }

    applyConfig(config) {

        if (this.isElementExists(config)) {

            this.setCustomConfig(config);

            //Setup of the given work mode
            this.setWorkMode(this.config.workMode);

        } else {

            //Setup of the default config and work mode

            this.setDefaultConfig();

            this.setWorkMode(this.config.workMode);

        }

        //Activating open all button (if present)
        if (this.config.openAllAccordion) {

            this.bindAccordionToggles($(this.config.openAllAccordion), 'open');

        }

        //Activating close all button (if present)
        if (this.config.closeAllAccordion) {

            // console.log('close presented')

            this.bindAccordionToggles($(this.config.closeAllAccordion), 'close');

        }

    }

    setCustomConfig(config) {
        //Selector for element to be opened after trigger fired
        this.config.openingElement = !config.openingElement ? 'ul' : config.openingElement;
        //Trigger element for opening menu
        this.config.triggerElement = !config.triggerElement ? 'button' : config.triggerElement;
        //Speed of the toggling animation
        this.config.toggleSpeed = !config['toggleSpeed'] ? 400 : config['toggleSpeed'];
        //For future extending
        this.config.workMode = !config['workMode'] ? 'default' : config['workMode'];
        //Active class for toggled element
        this.config.activeClass = !config['activeClass'] ? '_active' : config['activeClass'];
        //Active class for triggerElement
        this.config.activeClassTrigger = !config['activeClassTrigger'] ? '_activeTrigger' : config['activeClassTrigger'];
        //Element for closing all elements of the accordion
        this.config.closeAllAccordion = !config['closeAllAccordion'] ? false : config['closeAllAccordion'];
        //Element for applying class on opening any accordion element
        this.config.elementOnOpeningAccordion = !config['classOnOpeningAccordion'] ? false : config['classOnOpening'];
        //Applied classname on opening any accordion element
        this.config.classOnOpening = !config['classOnOpening'] ? '_opened' : config['classOnOpening'];
        //Element for opening all elements of the accordion
        this.config.openAllAccordion = !config['openAllAccordion'] ? false : config['openAllAccordion'];
        //Should tabs of the accordion be opened one by one only?
        this.config.oneTabMode = !config['oneTabMode'] ? false : config['oneTabMode'];
    }

    setDefaultConfig() {
        //Selector for element to be opened after trigger fired
        this.config.openingElement = 'ul';
        //Trigger element for opening menu
        this.config.triggerElement = 'button';
        //Speed of the toggling animation
        this.config.toggleSpeed = 400;
        //For future extending
        this.config.workMode = 'default';
        //Active class for toggled element
        this.config.activeClass = '_active';
        //Active class for triggerElement
        this.config.activeClassTrigger = '_activeTrigger';
        //Element for closing all elements of the accordion
        this.config.closeAllAccordion = false;
        //Element for applying class on opening any accordion element
        this.config.elementOnOpeningAccordion = false;
        //Applied classname on opening any accordion element
        this.config.classOnOpening = '_opened';
        //Element for opening all elements of the accordion
        this.config.openAllAccordion = false;
        //Should tabs of the accordion be opened one by one only?
        this.config.oneTabMode = false;
    }

    setWorkMode(workMode) {

        let data = {
            state: 'open',
            openingElement: this.config.openingElement,
            selector: this.selector,
            speed: this.config.toggleSpeed
        }

        if (workMode === 'opened') {

            this.toggleAllAccordion(false, data);

        }

    }

    isElementExists(element) {

        if (!element || typeof element === 'undefined') {

            return false;

        } else {

            return element;

        }

    }

    bindAccordionToggles(element, state) {

        if (element) {

            let data = this.getToggleAccordionConfig(state)

            this.clickEventBind(element, this.toggleAllAccordion, data);

        }

    }

    getToggleAccordionConfig(state) {

        return {
            state: state,
            triggerElement: this.config.triggerElement,
            activeClassTrigger: this.config.activeClassTrigger,
            openingElement: this.config.openingElement,
            selector: this.selector,
            speed: this.config.toggleSpeed
        }

    }

    clickEventBind(selector, methodName, data) {

        selector.on('click', (element) => {

            methodName(element, data);

        });

    }


    toggleSubmenus = (element, data) => {



        let nextToOpeningElement = $(element.currentTarget).next($(data.submenu));

        nextToOpeningElement.slideToggle(data.speed);

        $(element.currentTarget).toggleClass(data.activeClassTrigger);

        nextToOpeningElement.toggleClass(data.activeClass);

        if (data.oneTabMode) {

            if ($(element.currentTarget).hasClass(data.activeClassTrigger)) {

                //TODO: поработать на 29 строке. Неправильные свойства передаются в data

                console.log(data);

                $(data.openingElement).hide(data.speed);

            }

        }

    }


    toggleAllAccordion(element = null, data) {

        let menuTabs = $(data.selector).find($(data.openingElement));

        if (data.state === 'open') {

            if (data.triggerElement) {

                $(data.selector).find($(data.triggerElement)).addClass(data.activeClassTrigger);

            }

            menuTabs.slideDown(data.toggleSpeed);

        } else if (data.state === 'close') {

            if (data.triggerElement) {

                $(data.selector).find($(data.triggerElement)).removeClass(data.activeClassTrigger);

            }

            menuTabs.slideUp(data.toggleSpeed);

        }
    }

}

$('.nav-menu__sublist, .nav-menu__sublink-list').each(function () {
    if ($(this).hasClass('_active')) {
        $(this).show();
        $(this).prev().addClass('_activeTrigger');
    }
});
new AccordionMenu('.nav-menu__list', {
    'closeAllAccordion': '.nav-menu__btn',
});

new AccordionMenu('.course-program__list', {
    'openAllAccordion': '.course-program__link--open',
});

new AccordionMenu('.discussions__list', {
    'openingElement': '.discussions__item-text',
    'triggerElement': '.discussions__item-btn',
});

new AccordionMenu('.faq__list', {
    'openingElement': '.faq__item-text',
    'triggerElement': '.faq__item-btn',
});

new AccordionMenu('.catalogue__content');

new AccordionMenu('.platform__sections', {
    'openingElement': '.platform__section-dropdown',
    'triggerElement': '.platform__section-btn',
});
// custom scroll bar
function initCustomScrollBar() {

    new SimpleBar(headerNotificationContent[0], {

        autoHide: true

    });

}
// Custom style Select dropdown
function initCustomSelect() {

    $('.select-dropdown').styler();

    $('#user-gender, #signupform-gender').styler();

}
// Gallery
function initGallery() {

    $('[data-fancybox="certificates"]').fancybox({
        buttons: ["close"],
        loop: false,
        infobar: false,
    });


    // $(".fancybox-image").contextmenu(function (e) {
    //     console.log('2')
    //     e.preventDefault();
    // });


}

//  insert file image on Account Page
const accountImage = document.querySelector('.account__input-file'),

      accountPreview = document.querySelector('.account__img-preview');


if (accountImage) { 

    accountImage.addEventListener('change', () => {

        uploadFile(accountImage.files[0]);
    
    });
    
    
    function uploadFile(file) {
        
        let reader = new FileReader();
    
        reader.onload = function (e) {
    
            accountPreview.innerHTML= `<img src="${e.target.result}" alt="Фото">`;
            
        }
    
        reader.readAsDataURL(file);
    
    }

}


// menu burger
let headerMobileBtn = $('.header-mobile-btn'),

    headerMobileMenu = $('.header-mobile-content');


function initHeaderMobileBtn() {

    headerMobileBtn.on('click', function () {

        $(this).toggleClass('_active');

        headerMobileMenu.toggleClass('_active');

        body_lock(delay);

    });

}


function initResizeHeaderMobileBtn() {

    if (window.innerWidth > 775) {

        if (headerMobileBtn.hasClass('_active')) {

            headerMobileBtn.removeClass('_active');

            headerMobileMenu.removeClass('_active');

            body_lock_remove(delay);

        }

    }

}
let verticalMenuItem = $('.vertical-menu__list li'),

    verticalMenuMoreBtn = $('.vertical-menu__more-btn');


// Adaptive Vertical Menu
function initVerticalMenuResizeMore() {

    if (window.innerWidth < 992) {

        if (verticalMenuItem.length > 5) {

            verticalMenuItem.not(':nth-child(-n+4)').hide();

            verticalMenuMoreBtn.addClass('_active-more');

        }

    } else if (window.innerWidth > 992) {

        verticalMenuMoreBtn.removeClass('_active-more');

        verticalMenuItem.show();

    }

};


// hide vertical Menu on scroll down
function elementInViewport(el) {

    let bounds = el.getBoundingClientRect();

    return (
        (bounds.top + bounds.height > 0) &&
        (window.innerHeight - bounds.top > 0) &&
        (bounds.left + bounds.width > 0) &&
        (window.innerWidth - bounds.left > 0)
    );

}


const verticalMenu = document.querySelector('.vertical-menu');
const timer = document.querySelector('.timer');

let lastScrollTop = 0;


window.addEventListener('scroll', () => {

    if (window.innerWidth < 992) {

        if (verticalMenu) {

            let el = document.querySelector(".footer");
            let inViewport = elementInViewport(el);
            let scrollDistance = window.scrollY;

            if (scrollDistance > lastScrollTop) {

                verticalMenu.classList.remove('_active');

                if (el = inViewport) {

                    verticalMenu.classList.add('_active');
                }

            } else {

                verticalMenu.classList.add('_active');

            }

            if (timer) {
                if (verticalMenu.classList.contains('_active')) {
                    timer.classList.add('_active');
                } else {
                    timer.classList.remove('_active');
                }
            }

            lastScrollTop = scrollDistance;


        }

    } else if (window.innerWidth > 992) {
        if (verticalMenu) {
            verticalMenu.classList.remove('_active');
        }
        if (timer) {
            timer.classList.remove('_active');
        }
    }

});
// Dropdown event binding for opening/closing
function initDropdowns(dropdownBtn) {

    dropdownBtn.each(function () {

        let dropdownWrapper = $(this).parent(),
            dropdownContent = $(this).parent().find($('.dropdown--content'));

        $(this).on('click', function () {

            dropdownWrapper.toggleClass('_active');

            if (dropdownWrapper.hasClass('_active')) {

                $(document).mouseup(function (e) {

                    if (dropdownContent.has(e.target).length === 0 & dropdownWrapper.has(e.target).length === 0) {

                        dropdownContent.parent().removeClass('_active');

                        $(document).unbind('mouseup');

                    }

                });

            }

        });

    });

}


function achievementsDropdown() {
    let achievementsDropdownBtn = $('.achievements-dropdown-btn'),
        achievementsProgressBtn = $('.achievements__progress-btn'),
        achievementsLeaguesBtn = $('.achievements-leagues__btn');

    if ($('.nav-menu').find('.achievements__progress-list .achievements__progress-item').length > 7) {
        achievementsProgressBtn.addClass('_btn');
    }

    if ($('.nav-menu').find('.achievements-leagues__list .achievements-leagues__item').length > 5) {
        achievementsLeaguesBtn.addClass('_btn');
    }

    if ($('.account').find('.achievements__progress-list .achievements__progress-item').length > 6) {
        achievementsProgressBtn.addClass('_btn');
    }

    if ($('.account').find('.achievements-leagues__list .achievements-leagues__item').length > 4) {
        achievementsLeaguesBtn.addClass('_btn');
    }

    achievementsDropdownBtn.each(function () {
        $(this).on('click', function () {
            $(this).toggleClass('_active');
            $(this).parent().find('.achievements-dropdown-content').slideToggle();
        });
    });

}
// Modal
const modalBtn = document.querySelectorAll('.btn-modal'),
    modalOverlay = document.querySelectorAll('.modal__content'),
    overlay = document.querySelector('.overlay'),
    modalBtnClose = document.querySelectorAll('.modal__btn-close');


modalBtn.forEach((el) => {

    el.addEventListener('click', (e) => {

        document.querySelector('.modal').classList.add('_active');

        overlay.classList.add('_active');

        body_lock_add(350);

    });

});


function closeModalIfHasClassNavMenu() {

    if (document.querySelector('.nav-menu')) {

        if (!document.querySelector('.nav-menu').classList.contains('_resize')) {

            body_lock_remove(350);

        }

    }else {

        body_lock_remove(350);

    }

}

modalOverlay.forEach((el) => {

    el.addEventListener('click', (e) => {

        if (e.target == el) {

            el.closest('.modal').classList.remove('_active');

            overlay.classList.remove('_active');

            closeModalIfHasClassNavMenu();

        }

    });

});


modalBtnClose.forEach((el) => {

    el.addEventListener('click', (e) => {

        el.closest('.modal').classList.remove('_active');

        overlay.classList.remove('_active');

        closeModalIfHasClassNavMenu();

    });

});
let mainSlider = document.querySelector('.main-slider__container'),
    reviewsSlider = document.querySelector('.reviews__container'),
    courseCreatorSlider = document.querySelector('.course-creator__wrapper'),
    courseCreatorSliderMobile,
    readMoreSlider = document.querySelector('.read-more__container'),
    readMoreSliderMobile,
    accountCertificatesSlider = document.querySelector('.account__certificates-container'),
    accountCertificatesSliderMobile;

function initMainSlider() {
    new Swiper(mainSlider, {
        watchOverflow: true,
        autoHeight: true,
        effect: 'fade',
        pagination: {
            el: '.main-slider__pagination',
            clickable: true,
        },
    });
}

function initReviewsSlider() {
    let reviewsBtns = document.querySelector('.reviews__buttons'),
        reviewsSlide = document.querySelectorAll('.reviews__item:not(.swiper-slide-duplicate)');

    if (window.innerWidth > 576) {

        if (reviewsSlide.length > 2) {
            reviewsBtns.classList.add('_active');
        } else {
            reviewsBtns.classList.remove('_active');
        }

    } else if (window.innerWidth < 576) {

        if (reviewsSlide.length > 1) {
            reviewsBtns.classList.add('_active');
        } else {
            reviewsBtns.classList.remove('_active');
        }

    }

    new Swiper(reviewsSlider, {
        watchOverflow: true,
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        slidesPerView: 'auto',
        centeredSlides: true,

        navigation: {
            nextEl: '.reviews__buttons-next',
            prevEl: '.reviews__buttons-prev',
        },

        breakpoints: {
            320: {
                slidesPerView: 2,
                spaceBetween: 15,
                slidesPerView: 'auto',
                centeredSlides: false,
            },

            480: {
                slidesPerView: 2.9,
                spaceBetween: 30,
                slidesPerView: 'auto',
                centeredSlides: true,
            },
        },

        autoplay: {
            delay: 3000,
        },
    });
}

function initCourseCreatorSlider() {
    let courseCreatorBtns = document.querySelector('.course-creator__buttons'),
        courseCreatorItem = document.querySelectorAll('.course-creator__item:not(.swiper-slide-duplicate)');


    if (window.innerWidth < 576) {

        if (courseCreatorItem.length > 1) {
            courseCreatorBtns.classList.add('_active');
        } else {
            courseCreatorBtns.classList.remove('_active');
        }

    } else {

        courseCreatorBtns.classList.remove('_active');

    }

    if (window.innerWidth <= 576 && courseCreatorSlider.dataset.mobile == 'false') {
        courseCreatorSliderMobile = new Swiper(courseCreatorSlider, {
            watchOverflow: true,
            loop: false,
            slidesPerView: 1,
            spaceBetween: 30,
            slidesPerView: 'auto',
            centeredSlides: false,
            navigation: {
                nextEl: '.course-creator__buttons-next',
                prevEl: '.course-creator__buttons-prev',
            },
            breakpoints: {
                320: {
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 15,
                    slidesPerView: 'auto',
                    centeredSlides: false,
                },
                480: {
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 30,
                    slidesPerView: 'auto',
                    centeredSlides: false,
                },
            },
            autoplay: {
                delay: 3000,
            },
        });

        courseCreatorSlider.dataset.mobile = 'true';
    }

    if (window.innerWidth > 576) {
        courseCreatorSlider.dataset.mobile = 'false';
        if (courseCreatorSlider.classList.contains('swiper-container-initialized')) {
            courseCreatorSliderMobile.destroy();
        }
    }
}

function initReadMoreSlider() {
    if (window.innerWidth <= 992 && readMoreSlider.dataset.mobile == 'false') {
        readMoreSliderMobile = new Swiper(readMoreSlider, {
            watchOverflow: true,
            slidesPerView: 3,
            spaceBetween: 30,
            loop: true,
            slidesPerView: 'auto',
            centeredSlides: true,
            breakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                    slidesPerView: 'auto',
                    centeredSlides: false,
                },

                480: {
                    slidesPerView: 2.9,
                    spaceBetween: 30,
                    slidesPerView: 'auto',
                    centeredSlides: true,
                },
            },
            autoplay: {
                delay: 3000,
            },
        });

        readMoreSlider.dataset.mobile = 'true';
    }

    if (window.innerWidth > 992) {
        readMoreSlider.dataset.mobile = 'false';
        if (readMoreSlider.classList.contains('swiper-container-initialized')) {
            readMoreSliderMobile.destroy();
        }
    }
}

function initAccountCertificatesSlider() {
    let accountBtns = document.querySelector('.account__buttons'),
        accountItem = document.querySelectorAll('.account__certificates li:not(.swiper-slide-duplicate)');


    if (window.innerWidth < 576) {

        if (accountItem.length > 1) {
            accountBtns.classList.add('_active');
        } else {
            accountBtns.classList.remove('_active');
        }

    } else {

        accountBtns.classList.remove('_active');

    }


    if (window.innerWidth <= 576 && accountCertificatesSlider.dataset.mobile == 'false') {
        accountCertificatesSliderMobile = new Swiper(accountCertificatesSlider, {
            watchOverflow: true,
            loop: false,
            spaceBetween: 30,
            slidesPerView: 'auto',
            centeredSlides: true,
            navigation: {
                nextEl: '.account__buttons-next',
                prevEl: '.account__buttons-prev',
            },
            breakpoints: {
                320: {
                    spaceBetween: 15,
                    slidesPerView: 'auto',
                    centeredSlides: false,
                },
                350: {
                    spaceBetween: 30,
                    slidesPerView: 'auto',
                    centeredSlides: true,
                },

            },
            // autoplay: {
            //     delay: 3000,
            // },
        });

        accountCertificatesSlider.dataset.mobile = 'true';
    }

    if (window.innerWidth > 576) {
        accountCertificatesSlider.dataset.mobile = 'false';
        if (accountCertificatesSlider.classList.contains('swiper-container-initialized')) {
            accountCertificatesSliderMobile.destroy();
        }
    }
}
// Hide modal for swipe

$('.vertical-menu, .vertical-menu__btn').swipe({

    swipeUp: function () {

        navMenu.addClass('_resize');

        $('.nav-overlay').addClass('_active');

        body_lock_add(delay);

    }

});


$('.nav-menu__btn-resize').swipe({

    swipeDown: function () {

        navMenu.removeClass('_resize');

        $('.nav-overlay').removeClass('_active');

        body_lock_remove(delay);

    }

});
function initPageScroll2id() {
    $('.course-info__link').mPageScroll2id({

        scrollSpeed: 800,
        offset: 0,

    });
}

// phone mask
let phoneMask = document.querySelectorAll('.phone_mask'),
    dateMask = document.querySelectorAll('.date_mask');

function initPhoneMask() {
    if (phoneMask) {
        phoneMask.forEach(el => {
            IMask(
                el, {
                mask: '+{998}(00)000-00-00'
            });
        });
    }
}

function initDateMask() {
    if(dateMask) {
        dateMask.forEach(el => {
            IMask(
                el, {
                    mask: Date,
                    min: new Date(1900, 0, 1),
                    max: new Date(2020, 0, 1),
                    lazy: true
                }
            )
        });
    }
}
function initTimer() {
    $('.timer').startTimer({
        onComplete: function () {
            $('#unit-form').trigger('submit');
        }
    });
}

let testTime;

$(document).on('click', '.timer-launcher-start-btn', function () {
    testTime = parseFloat($('[name="test_time"]').val());

    if (testTime > 0) {
        $('.timer').data('minutes-left', testTime).show();
        $('.timer-launcher').hide();
        initTimer();
    }
});

$(document).on('click', '.timer-launcher-close-btn', function () {
    $('.timer-launcher').hide();
});
function unitGallery() {
    $('.unit__content img').each(function () {

        const randomId = () => {
            return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        };

        let thisSrc = $(this).attr("src");

        $(this).wrap(`<a class="unit__content-img" href="${thisSrc}"></a>`);

        $(this).closest('.unit__content-img').attr('data-fancybox', randomId);

        $('[data-fancybox]').fancybox({
            afterShow: function () {
                $(".fancybox-image").contextmenu(function (e) {
                    e.preventDefault();
                });
            },
        });

    });

    for (const el of document.querySelectorAll('.unit__content-img img')) {
        el.addEventListener("contextmenu", function (e) {
            e.preventDefault();
        });
    }
}


$(document).ready(function () {

    // shows or hides the counter in the header
    headerNavNum($('.header-notification__num'));
    headerNavNum($('.header-login__cart-num'));

    //init Header mobile btn on adaptive
    initHeaderMobileBtn();

    // init Custom Selects
    initCustomSelect();

    // init Gallery
    initGallery();

    // init Vertical Menu resize
    initVerticalMenuResizeMore();

    // init header notification Mobile
    headerNotificationAttr();

    // init Dropdowns
    initDropdowns($('.header-notification__btn'));
    initDropdowns($('.dropdown-user__btn'));
    initDropdowns($('.dropdown-language__btn'));
    initDropdowns($('.dropdown-course__btn'));
    initDropdowns($('.course-card__menu-btn'));

    //page scroll to id
    initPageScroll2id();

    // init Datepicker
    initDateMask();

    //init phone mask
    initPhoneMask();

    unitGallery();

    achievementsDropdown();

    // init Header Notification Content custom scrollbar
    if ($('.header-login__notification').length > 0) {

        initCustomScrollBar();

    }

    // init Sliders
    if (mainSlider) {
        initMainSlider();
    }

    if (reviewsSlider) {
        initReviewsSlider();
    }

    if (courseCreatorSlider) {
        initCourseCreatorSlider();
    }

    if (readMoreSlider) {
        initReadMoreSlider();
    }

    if (accountCertificatesSlider) {
        initAccountCertificatesSlider();
    }

    // init Slider window resize
    window.addEventListener('resize', () => {
        if (courseCreatorSlider) {
            initCourseCreatorSlider();
        }

        if (readMoreSlider) {
            initReadMoreSlider();
        }

        if (accountCertificatesSlider) {
            initAccountCertificatesSlider();
        }
    });


    // Ajax Complete
    $(document).on('ajaxComplete', function () {

        initCustomSelect();
        initGallery();
        headerNavNum($('.header-notification__num'));
        headerNavNum($('.header-login__cart-num'));

        if ($('.modal').hasClass('_active')) {
            body_lock_add(delay);
            body_lock_remove(delay);
            
            $('.overlay').addClass('_active');
        }

    });

    // Resize
    $(window).on('resize', function () {

        initResizeHeaderMobileBtn();
        initVerticalMenuResizeMore();
        headerNotificationAttr();

    });
});
