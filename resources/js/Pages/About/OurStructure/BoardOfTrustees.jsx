import { FaInstagram, FaLinkedin, FaXTwitter } from 'react-icons/fa6';

export default function BoardOfTrustees() {
    const trustees = [
        {
            name: 'Wade Warren',
            role: 'Chairperson',
            image: 'https://images.unsplash.com/photo-1527980965255-d3b416303d12',
        },
        {
            name: 'Wade Warren',
            role: 'Deputy Chairperson',
            image: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d',
        },
        {
            name: 'Wade Warren',
            role: 'Legal professionals',
            image: 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e',
        },
    ];

    return (
        <section className="bg-white py-20">
            <div className="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-4 gap-16">

                {/* LEFT CONTENT */}
                <div className="lg:col-span-3">
                    <h1 className="text-3xl font-bold text-gray-900 mb-6">
                        Our Board of Trustees
                    </h1>

                    <p className="text-gray-600 leading-relaxed mb-6 max-w-3xl">
                        The Board of Trustees serves as the strategic oversight body of IFPN and is
                        responsible for guiding the long-term direction of the Institute. The board
                        ensures that the IFPN remains aligned with its mission and vision while
                        upholding ethical standards.
                    </p>

                    <h3 className="font-semibold text-gray-900 mb-3">
                        Key Responsibilities:
                    </h3>

                    <ul className="list-disc pl-6 text-gray-600 space-y-2 mb-16">
                        <li>Establishing the strategic vision and mission of IFPN.</li>
                        <li>Overseeing governance policies and ensuring sustainability.</li>
                        <li>Approving annual budgets and financial reports.</li>
                        <li>Appointing council members and leadership succession.</li>
                        <li>Ensuring compliance with Nigerian and international standards.</li>
                    </ul>

                    {/* TRUSTEES */}
                    {/* <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
                        {trustees.map((member, index) => (
                            <div key={index} className="text-center">
                                <img
                                    src={member.image}
                                    alt={member.name}
                                    className="w-40 h-40 rounded-full object-cover mx-auto mb-6"
                                />

                                <h4 className="font-semibold text-gray-900 text-lg">
                                    {member.name}
                                </h4>
                                <p className="text-gray-500 mb-4">{member.role}</p>

                                <div className="flex justify-center gap-4 text-gray-700">
                                    <FaInstagram className="cursor-pointer hover:text-black" />
                                    <FaLinkedin className="cursor-pointer hover:text-black" />
                                    <FaXTwitter className="cursor-pointer hover:text-black" />
                                </div>
                            </div>
                        ))}
                    </div> */}

                    <h1 className="text-3xl font-bold text-gray-900 mb-6">
                        The Governing Council
                    </h1>

                    <p className="text-gray-600 leading-relaxed mb-6 max-w-3xl">
                        The Governing Council is responsible for the formulation and implementation of the Institute's policies, providing direction for the activities of the Executive Management, and overseeing the Institute's core functions, including membership, education, certification, and research. 
                    </p>

                    <h3 className="font-semibold text-gray-900 mb-3">
                        Key Responsibilities:
                    </h3>

                    <ul className="list-disc pl-6 text-gray-600 space-y-2 mb-16">
                        <li>Establishing the strategic vision and mission of IFPN.</li>
                        <li>Overseeing governance policies and ensuring sustainability.</li>
                        <li>Approving annual budgets and financial reports.</li>
                        <li>Appointing council members and leadership succession.</li>
                        <li>Ensuring compliance with Nigerian and international standards.</li>
                    </ul>

                </div>

                {/* RIGHT SIDEBAR */}
                <aside className="space-y-14">
                    <div>
                        <h4 className="font-semibold text-lg mb-4">People</h4>
                        <ul className="space-y-3 text-blue-950">
                            <li className="font-medium cursor-pointer">Board of Trustees</li>
                            <li className="cursor-pointer hover:underline">
                                The Governing Council
                            </li>
                            <li className="cursor-pointer hover:underline">
                                Advisory Committees
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="font-semibold text-lg mb-3">Need Help?</h4>
                        <p className="text-gray-600 mb-3">Contact Us Here</p>
                        {/* <p className="text-blue-600 text-sm mb-1">
                            +234 (0) 915-341-4314
                        </p> */}
                        <p className="text-blue-950 text-sm">
                            enquiries@igrfcp.org
                        </p>
                    </div>
                </aside>

            </div>
        </section>
    );
}
