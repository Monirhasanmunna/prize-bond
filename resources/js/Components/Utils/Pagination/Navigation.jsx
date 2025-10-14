function Navigation(props) {
    const numberOfPages = Math.ceil(props.count / props.length);
    let resultArray = Array.from({ length: numberOfPages }, (_, i) => i + 1);
    if (numberOfPages > 4) {
        const firstSegment = Array.from({ length: numberOfPages }, (_, i) => i + 1).filter((_, i) => i < 2);
        const midSegment = [Number(props.page) - 1, Number(props.page), Number(props.page) + 1];
        const lastSegment = Array.from({ length: numberOfPages }, (_, i) => i + 1).filter((_, i, arr) => i >= arr.length - 2);
        if (midSegment[0] < 1) midSegment[0] += 3;
        if (midSegment[2] > numberOfPages) midSegment[2] -= 3;
        resultArray = Array.from(new Set([...firstSegment, ...midSegment, ...lastSegment]))
            .filter(i => i > 0 && i <= numberOfPages)
            .sort((a, b) => a - b);
        resultArray = insertDots(resultArray, Number(props.page));
    }

    return (
        <div className="py-1 px-4">
            <nav className="flex items-center space-x-1" aria-label="Pagination">
                <button
                    type="button"
                    onClick={() => {
                        let cp = props.page > 1 ? Number(props.page) - 1 : props.page;
                        props.callback(cp);
                    }}
                    aria-label="Previous"
                    disabled={props.page <= 1}
                    className="p-2.5 min-w-10 inline-flex justify-center items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                >
                    <span aria-hidden="true">«</span>
                    <span className="sr-only">Previous</span>
                </button>

                {resultArray.map((pageNumber, i) => (
                    <button
                        key={i}
                        type="button"
                        aria-current={props.page == pageNumber ? "page" : undefined}
                        onClick={() =>
                            props.callback(pageNumber === '.' ? Math.floor((resultArray[i - 1] + resultArray[i + 1]) / 2) : pageNumber)
                        }
                        className={`min-w-7 flex justify-center items-center h-7 text-sm rounded-full ${
                            props.page == pageNumber
                                ? "bg-gray-600 text-gray-800 dark:bg-neutral-500 dark:text-white text-[16px] font-bold"
                                : "text-gray-800 hover:bg-gray-600 dark:bg-neutral-500 focus:outline-none focus:bg-gray-100 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                        } disabled:opacity-50 disabled:pointer-events-none`}
                    >
                        {pageNumber}
                    </button>
                ))}

                <button
                    type="button"
                    onClick={() => {
                        let cp = props.page < numberOfPages ? Number(props.page) + 1 : props.page;
                        props.callback(cp);
                    }}
                    aria-label="Next"
                    disabled={props.page >= numberOfPages}
                    className="p-2.5 min-w-10 inline-flex justify-center items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                >
                    <span className="sr-only">Next</span>
                    <span aria-hidden="true">»</span>
                </button>
            </nav>
        </div>
    );
}

function insertDots(arr, ref) {
    const dotted = [];

    for (let i = 0; i < arr.length; i++) {
        dotted.push(arr[i]);

        if (i < arr.length - 1 && arr[i + 1] - arr[i] !== 1) {
            dotted.push('.');
        }
    }

    let refIndex = dotted.indexOf(ref);
    let dotIndex = dotted.indexOf('.');
    if (dotIndex < 0) {
        return dotted;
    }

    let seg1 = dotted.slice(0, dotIndex);
    let seg2 = dotted.slice(dotIndex + 1);

    let dot2Index = seg2.indexOf('.');
    let result = [];
    if (dot2Index > -1) {
        let seg3 = seg2.slice(dot2Index + 1);
        seg2 = seg2.slice(0, dot2Index);
        result = seg2;
        result.unshift('.');
        if (seg1.length) result.unshift(seg1[0]);
        result.push('.');
        if (seg3.length) result.push(seg3[seg3.length - 1]);
    } else {
        if (refIndex < dotIndex) {
            if (seg1.length) result = seg1;
            result.push('.');
            if (seg2.length) result.push(seg2[seg2.length - 1]);
        } else if (refIndex > dotIndex) {
            if (seg2.length) result = seg2;
            result.unshift('.');
            if (seg1.length) result.unshift(seg1[0]);
        }
    }

    return result;
}

export default Navigation;
