import {toast} from "react-toastify";

export const notify = (data) => {
    if (data.success) {
        toast.success(data.message, {
            position: "top-right",
            autoClose: 2000,
        });
    } else {
        toast.error(data.message, {
            position: "top-right",
            autoClose: 2000,
        });
    }
}

export const setPaginationFromSessionStorage = (setPagination, sessionKey, ref) => {
    let paginationSessionData = sessionStorage.getItem(sessionKey)
    if (paginationSessionData) {
        paginationSessionData = JSON.parse(paginationSessionData)
        if (paginationSessionData.page)       ref.page          = paginationSessionData.page
        if (paginationSessionData.length)     ref.length        = paginationSessionData.length
        if (paginationSessionData.status)     ref.status        = paginationSessionData.status
        if (paginationSessionData.searchText) ref.searchText    = paginationSessionData.searchText
    }

    setPagination({...ref})
}

export const deepUpdate = (target, source) => {
    if (target === null) return source

    // if type
    if (typeof target !== typeof source) {

        if (!(
            ['number','string'].includes(typeof target)
            && ['number','string'].includes(typeof target)
        )) return target

    }

    // if type same
    if (target instanceof Array && source instanceof Array) return source;
    // if type not array
    if (typeof target !== 'object') return source;
    // if type object

    const output = {...target};
    for (const key of Object.keys(target)) {
        output[key] = deepUpdate(target[key], source[key]);
    }

    return output;
};



        export const convertToLocalDateTime = input => {
    const date = new Date(input);
    const options = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    };
    const formattedDate = date.toLocaleString('en-US', options);
    const [datePart, timePart] = formattedDate.split(', ');
    const [month, day, year] = datePart.split('/');
    return `${timePart}, ${day}-${month}-${year}`;
}

export const getQueries = (key) => {
    const getItem = sessionStorage.getItem(key)
    if(!getItem) return null;

    return JSON.parse(getItem);
}

export const parseImage = (images = {}, index = 0) => {
    let image = null;
    if(images !== null && images.length > 0){
        const parse = JSON.parse(images)
        image = parse[index]?.path
    }

    return image ? image : null;
}

export function generateUniqueNumericId() {
    return Date.now() + Math.floor(Math.random() * 1000).toString();
}

export function convertTo12Hour(timeStr) {
    const [hourStr, minute, second] = timeStr.split(":");
    let hour = parseInt(hourStr, 10);
    const ampm = hour >= 12 ? "PM" : "AM";
    hour = hour % 12 || 12;
    return `${hour}:${minute} ${ampm}`;
}

export function formatDate(dateStr) {
    const date = new Date(dateStr);

    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    const day = String(date.getDate()).padStart(2, '0');
    const month = months[date.getMonth()];
    const year = String(date.getFullYear()).slice(-2);

    return `${day} ${month} ${year}`;
}
