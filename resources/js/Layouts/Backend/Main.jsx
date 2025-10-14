import Header from "@/Layouts/Backend/Header.jsx";
import Sidebar from "@/Layouts/Backend/Sidebar.jsx";
import Notifier from "@/Components/Utils/Notification/Notifier.jsx";
import {Head, usePage} from "@inertiajs/react";

export default function Main({children}){
    setTimeout(() => window.HSStaticMethods.autoInit(), 100)

    const {app_settings, fileBase} = usePage().props
    const favicon = app_settings['favicon'] ? JSON.parse(app_settings['favicon']['value']) : null;

    return (
        <>
            <Head>
                <link rel="icon" type="image/svg+xml" href={fileBase + '/' +favicon?.path} />
            </Head>

            <Header/>
            <Sidebar/>
            <Notifier/>
            <main
                className="lg:hs-overlay-layout-open:pl-60 bg-gray-100  transition-all duration-300 lg:fixed lg:inset-0 pt-13 px-3 pb-3 dark:bg-neutral-900">
                <div
                    className="h-[calc(100dvh-62px)] lg:h-full overflow-y-auto flex flex-col bg-white border border-gray-200 shadow-xs rounded-lg dark:bg-neutral-800 dark:border-neutral-700">
                    <div className="flex-1 p-4 flex flex-col overflow-y-auto [&::-webkit-scrollbar]:w-0">
                        <div className="w-full">
                            {children}
                        </div>
                    </div>
                </div>
            </main>
        </>
    )
}
