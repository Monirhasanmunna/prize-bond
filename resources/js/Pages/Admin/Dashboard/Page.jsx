import Main from "@/Layouts/Backend/Main.jsx";

export default function Page(){
    return (
        <Main>
            <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 p-5">
                <div className="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
                    <div className="p-4 md:p-5">
                        <div className="flex items-center gap-x-2">
                            <p className="text-xs uppercase text-gray-500 dark:text-neutral-500">
                                Unique User
                            </p>
                            <div className="hs-tooltip">
                                <div className="hs-tooltip-toggle">
                                    <svg className="shrink-0 size-4 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                        <path d="M12 17h.01" />
                                    </svg>
                                    <span className="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700" role="tooltip">
                                    The number of unique users
                                  </span>
                                </div>
                            </div>
                        </div>

                        <div className="mt-1 flex items-center gap-x-2">
                            <h3 className="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                10
                            </h3>

                        </div>
                    </div>
                </div>

                <div className="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
                    <div className="p-4 md:p-5">
                        <div className="flex items-center gap-x-2">
                            <p className="text-xs uppercase text-gray-500 dark:text-neutral-500">
                                Total Contact
                            </p>
                            <div className="hs-tooltip">
                                <div className="hs-tooltip-toggle">
                                    <svg className="shrink-0 size-4 text-gray-500 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                        <path d="M12 17h.01" />
                                    </svg>
                                    <span className="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700" role="tooltip">
                                    The number of total contact
                                  </span>
                                </div>
                            </div>
                        </div>

                        <div className="mt-1 flex items-center gap-x-2">
                            <h3 className="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
                                10
                            </h3>

                        </div>
                    </div>
                </div>
            </div>
        </Main>
    )
}
