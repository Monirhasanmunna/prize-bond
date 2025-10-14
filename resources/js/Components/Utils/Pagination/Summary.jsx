function Summary(props) {
    return (
        <div>
            <p className="text-sm text-neutral-700 dark:text-neutral-200 flex items-center gap-1">
                Showing
                <span className="font-medium">{(props.page - 1) * props.length + 1}</span>
                to
                <span className="font-medium">
                                    {props.count < props.page * props.length
                                        ? props.count
                                        : props.page * props.length}
                                </span>
                of
                <span className="font-medium">{props.count}</span>
                records
            </p>
        </div>
    );
}

export default Summary;
