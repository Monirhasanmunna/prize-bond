const StatusFilter = ({status, setPagination, pagination, setGo, segment}) => {
    return (
        <div className="flex items-center justify-end border border-gray-400 rounded-sm overflow-hidden">
            {Object.keys(status).map((key, index) => {
                const statusText = status[key];
                const isActive = pagination.status === key;

                return (
                    <button
                        key={key}
                        style={{
                            backgroundColor: isActive ? "#000" : "#fff",
                            color: isActive ? "#fff" : "#000",
                        }}
                        className={`cursor-pointer px-4 py-[7px] transition-colors hover:bg-gray-100
                                        ${index !== Object.keys(status).length - 1 ? 'border-r border-gray-400' : ''}
                                    `}
                        onClick={() => {
                            setPagination((state) => ({ ...state, status: `${key}` }));
                            setGo(true);
                        }}
                        type="button"
                    >
                        {statusText}
                    </button>
                );
            })}
            <button
                style={{
                    backgroundColor: pagination.status == null ? "#000" : "#fff",
                    color: pagination.status == null ? "#fff" : "#000",
                }}
                className="cursor-pointer capitalize px-4 py-[7px] transition-colors hover:bg-gray-100"
                onClick={() => {
                    setPagination((state) => ({ ...state, status: null }));
                    setGo(true);
                }}
                type="button"
            >
                All {segment}
            </button>
        </div>
    )
}


export default StatusFilter;
