<div className="py-1 px-4">
                            <button 
                                className="font-medium text-gray-700 py-2 w-full text-left flex justify-between items-center"
                                onClick={() => setOpenDropdown(openDropdown === 'programmes' ? null : 'programmes')}
                            >
                                Programmes & Courses
                                <svg className={`w-4 h-4 transition-transform duration-300 ${openDropdown === 'programmes' ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {openDropdown === 'programmes' && (
                                <div className="pl-4 space-y-1 border-l-2 border-gray-200 mt-2">
                                    <div className="text-xs uppercase text-blue-900 font-semibold tracking-wide py-2">
                                        Core Programme Pathways
                                    </div>
                                    
                                    <Link 
                                        href="/programmes/grc" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-blue-600 rounded-full mr-3"></div>
                                            GRC & Risk Management
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/financial-crime" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-red-600 rounded-full mr-3"></div>
                                            Financial Crime Prevention
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/crypto" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-purple-600 rounded-full mr-3"></div>
                                            Crypto & Digital Assets
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/cybersecurity" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-green-600 rounded-full mr-3"></div>
                                            Cybersecurity & Digital Risk
                                        </div>
                                    </Link>
                                    
                                    <Link 
                                        href="/programmes/ai" 
                                        className="block text-gray-600 hover:text-blue-900 py-2 px-3 hover:bg-gray-50 rounded transition duration-200"
                                        onClick={() => {
                                            setIsMobileMenuOpen(false);
                                            setOpenDropdown(null);
                                        }}
                                    >
                                        <div className="flex items-center">
                                            <div className="w-2 h-2 bg-yellow-600 rounded-full mr-3"></div>
                                            AI & Emerging Technology
                                        </div>
                                    </Link>
                                    
                                    <div className="border-t border-gray-200 my-2 pt-2">
                                        <Link 
                                            href="/programmes/all-courses" 
                                            className="block text-blue-900 font-medium py-2 px-3 hover:bg-blue-50 rounded transition duration-200"
                                            onClick={() => {
                                                setIsMobileMenuOpen(false);
                                                setOpenDropdown(null);
                                            }}
                                        >
                                            View All Programmes
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </div>