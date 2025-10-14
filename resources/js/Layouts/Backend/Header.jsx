import UserDropdown from "@/Components/Backend/Header/UserDropdown.jsx";
import {Link, usePage} from "@inertiajs/react";
import {FaRegUserCircle} from "react-icons/fa";

export default function Header(){
    const {logo, fileBase} = usePage().props

    return (
        <header
            className="fixed top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-[50] w-full bg-zinc-100 text-sm py-2.5 dark:bg-neutral-900">
            <nav className="px-4 sm:px-5.5 flex basis-full items-center w-full mx-auto">
                <div className="w-full flex items-center gap-x-1.5">
                    <ul className="flex lg:justify-between items-center gap-1.5 w-[225px]">
                        <li className="inline-flex items-center relative text-gray-200 pe-1.5  dark:text-neutral-200 dark:after:bg-neutral-700">
                            <div className="shrink-0 w-24">
                                <img src={`${fileBase}/${logo?.path}`} alt="logo"/>
                            </div>

                            <div className="hidden sm:block ms-1">

                            </div>
                        </li>

                        <li>
                            <button type="button"
                                    className="p-1.5 size-7.5 inline-flex items-center gap-x-1 text-xs rounded-md border border-transparent text-gray-500 hover:text-gray-800 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:text-gray-800 dark:text-neutral-500 dark:hover:text-neutral-400 dark:focus:text-neutral-400"
                                    aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-pro-sidebar"
                                    data-hs-overlay="#hs-pro-sidebar">
                                <svg className="shrink-0 size-4.5" xmlns="http://www.w3.org/2000/svg" width="28"
                                     height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"
                                     strokeLinecap="round" strokeLinejoin="round">
                                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                                    <path d="M15 3v18"/>
                                    <path d="m10 15-3-3 3-3"/>
                                </svg>
                                <span className="sr-only">Sidebar Toggle</span>
                            </button>

                        </li>
                    </ul>

                    <ul className="flex flex-row items-center gap-x-3 ms-auto">
                        <li className="inline-flex items-center gap-1.5 relative text-gray-500 pe-3 last:pe-0 last:after:hidden after:absolute after:top-1/2 after:end-0 after:inline-block after:w-px after:h-3.5 after:bg-gray-300 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-neutral-200 dark:after:bg-neutral-700">
                            <div className="h-8">
                                <div
                                    className="hs-dropdown inline-flex [--strategy:absolute] [--auto-close:inside] [--placement:bottom-right] relative text-start">
                                    <button id="hs-dnad" type="button"
                                            className="p-0.5 inline-flex shrink-0 items-center gap-x-3 text-start rounded-full hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 dark:hover:bg-neutral-800 dark:hover:text-neutral-200 dark:focus:bg-neutral-800 dark:focus:text-neutral-200 dark:text-neutral-500"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        <FaRegUserCircle className={`size-7`} />
                                    </button>

                                    <div className="hs-dropdown-menu hs-dropdown-open:opacity-100 w-60 transition-[opacity,margin] duration opacity-0 hidden z-20 bg-white border border-gray-200 rounded-xl shadow-xl dark:bg-neutral-900 dark:border-neutral-700"
                                        role="menu" aria-orientation="vertical" aria-labelledby="hs-dnad">
                                        <div className="p-1 border-t border-gray-200 dark:border-neutral-800">
                                            <Link href={route('admin.profile.page')} className="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                <svg className="shrink-0 mt-0.5 size-4"
                                                     xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </svg>
                                                Profile
                                            </Link>
                                            <Link href={route('logout')} as={`button`} method={`post`} className="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                                                <svg className="shrink-0 mt-0.5 size-4"
                                                     xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                                    <path d="m16 17 5-5-5-5"/>
                                                    <path d="M21 12H9"/>
                                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                                </svg>
                                                Log out
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
    )
}
