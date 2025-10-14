export default function Button({className, type = 'submit', buttonText, isLoading = false, callback = null, icon = null}){
    return (
        <button type={type} onClick={callback} className={`w-[100px] h-[35px] flex items-center justify-center gap-x-2 bg-blue-500 text-white rounded-[2px] hover:bg-blue-600 duration-150 ${className}`}>
            {
                isLoading ? (
                    <div
                        className="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-blue-100 rounded-full dark:text-blue-500"
                        role="status" aria-label="loading">
                        <span className="sr-only">Loading...</span>
                    </div>
                ): <></>
            }

            {icon}
           <span>{buttonText}</span>
        </button>
    )
}
