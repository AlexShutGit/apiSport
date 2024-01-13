const buttons = document.querySelectorAll('.js-open-modal');
const elemForInsert = document.querySelector('.container');

buttons.forEach((item) => {
    item.addEventListener('click', () => {
        showModal(item);
    })
});

const showModal = (item) => {
    const userName = item.dataset.firstName + ' ' + item.dataset.secondName;
    const html = `
<div class="mask js-mask"></div>
<div class="modal_container js-modal-container">
    <div class="modal js-modal">
        <div class="modal__header">
            <button class="close-button js-close-button">
                <span class="close-button__elem close-button__elem--first-elem"></span>
                <span class="close-button__elem close-button__elem--second-elem"></span>
            </button>
        </div>
        <div class="modal__content">
            <div class="modal__name">
                ${userName}
            </div>
            <div class="modal__birthday">
                Дата рождения: ${item.dataset.birthday}
            </div>
            <div>
                Ссылка на личное дело: <a href="user/${item.dataset.id}"  class="modal__link">${userName}</a>
            </div>
        </div>
    </div>
</div>
`;
    elemForInsert.insertAdjacentHTML("beforebegin", html);
    initCloseModal();
}

const initCloseModal = () => {
    const closeButton = document.querySelector('.js-close-button');
    const modalContainer = document.querySelector('.js-modal-container');

    closeButton.onclick = () => {
        removeModal();
    }

    modalContainer.onclick = (event) => {
        if (event.target.querySelector('.js-modal')) {
            removeModal();
        }
    }
}

const removeModal = () => {
    const modalMask = document.querySelector('.js-mask');
    const modalMaskParent = modalMask.parentNode;
    const modalContainer = document.querySelector('.modal_container');
    const modalContainerParent = modalContainer.parentNode;
    modalMaskParent.removeChild(modalMask);
    modalContainerParent.removeChild(modalContainer);
}