/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */


$(function() {

    /* ES5, using Bluebird */
    var isMomHappy = true;

// Promise
    var willIGetNewPhone = new Promise(
        function (resolve, reject) {
            if (isMomHappy) {
                var phone = {
                    brand: 'Samsung',
                    color: 'black'
                };
                resolve(phone);
            } else {
                var reason = new Error('mom is not happy');
                reject(reason);
            }

        }
    );

// 2nd promise
    var showOff = function (phone) {
        var message = 'Hey friend, I have a new ' +
            phone.color + ' ' + phone.brand + ' phone';

        return Promise.resolve(message);
    };
    var askMom = function () {
        console.log('before ssasking Mom'); // log before
        willIGetNewPhone
            .then(showOff)
            .then(function (fulfilled) {
                console.log('ssdsd '+fulfilled);
            })
            .catch(function (error) {
                console.log(error.message);
            });
        console.log('after asking mom'); // log after
    }


    const greet = function(who)
    {
        console.log("Hello " + who);
    }


    $("#testBtn").on('click', function()  {
        greet("Harry");
        console.log("Bye");
        askMom();
    });



});

var $collectionHolder;

// setup an "add a tag" link
var $addTagButton = $('<button type="button" class="add_tag_link btn btn-primary">Add</button>');
var $newLinkLi = $('<li></li>').append($addTagButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.collection_class');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

