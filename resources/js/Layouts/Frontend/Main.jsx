import Header from "@/Layouts/Frontend/Header.jsx";
import Footer from "@/Layouts/Frontend/Footer.jsx";
import '@/../css/client.css';
import {Head, usePage} from "@inertiajs/react";
import Notifier from "@/Components/Utils/Notification/Notifier.jsx";

const Main = ({children}) => {
    setTimeout(() => window.HSStaticMethods.autoInit(), 100)

    const {app_settings, fileBase} = usePage().props
    const social_links = app_settings['social_links'] ? JSON.parse(app_settings['social_links']['value']) : null;
    const contact_info = app_settings['contact_info'] ? JSON.parse(app_settings['contact_info']['value']) : null;
    const copyright = app_settings['copyright'] ? JSON.parse(app_settings['copyright']['value']) : null;
    const favicon = app_settings['favicon'] ? JSON.parse(app_settings['favicon']['value']) : null;

    return (
        <div className="overflow-hidden bg-[#FFFFFF]">
            <Head>
                <link rel="icon" type="image/svg+xml" href={fileBase + '/' +favicon?.path} />
            </Head>
            <Notifier/>
            <div>
                <Header />
            </div>
            <div className="">
                {children}
            </div>
            <div>
                <Footer social_links={social_links} contact_info={contact_info} copyright={copyright} />
            </div>
        </div>
    )
}

export default Main
