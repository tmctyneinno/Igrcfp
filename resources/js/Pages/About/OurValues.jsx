import React from 'react';

const valuesData = [
  {
    title: 'Integrity',
    description:
      'We uphold the highest ethical standards in governance, compliance, and financial crime prevention. Integrity guides every action we take and every partnership we build.',
  },
  {
    title: 'Innovation',
    description:
      'We embrace new ideas, technologies, and approaches from RegTech to ESG frameworks to prepare professionals for the challenges of tomorrow.',
  },
  {
    title: 'Collaboration',
    description:
      'We believe progress comes from working together. IGRCFP connects regulators, institutions, and practitioners across continents to drive global impact.',
  },
  {
    title: 'Excellence',
    description:
      'We are committed to delivering world-class training, certifications, research, and events that set the benchmark for professional standards worldwide.',
  },
];

export default function OurValues() {
  return (
    <section className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Section Title */}
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-medium text-gray-900">Our Values</h2>
        </div>

        {/* Flexbox layout: Left - Values, Right - Image */}
        <div className="flex flex-col lg:flex-row items-center justify-between gap-12">
            {/* Left Side - Values Section */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5 flex-1 h-full">
                {valuesData.map((value, index) => (
                <div
                    key={index}
                    className="bg-gray-100 p-2.5 rounded-lg shadow-md text-center flex flex-col justify-between h-full"
                >
                    <div className="mb-0">
                        {/* Icon Placeholder */}
                        <div className="w-16 h-16 bg-gray-300 mx-auto mb-0 rounded-full flex items-center justify-center">
                            <span className="text-3xl text-gray-700">📦</span> {/* Example icon */}
                        </div>
                    </div>
                    <h3 className="text-xl font-semibold text-gray-900 mb-2">{value.title}</h3>
                    <p className="text-gray-600">{value.description}</p>
                </div>
                ))}
            </div>

            {/* Right Side - Image */}
            <div className="flex-1 h-full">
               <BackgroundVideo />
            </div>
        </div>


      </div>
    </section>
  );
}


export default function BackgroundVideo() {
  const videoRef = useRef(null);
  const [isPlaying, setIsPlaying] = useState(false);

  const handlePlay = () => {
    setIsPlaying(true);
    videoRef.current.play();
  };

  return (
    <div className="relative w-full h-full rounded-lg overflow-hidden">
      {/* Video */}
      <video
        ref={videoRef}
        className="w-full h-full object-cover"
        poster="assets/images/innerpage/bg/about-bg.png"
        controls={isPlaying}
      >
        <source
          src="assets/videos/about-video.mp4"
          type="video/mp4"
        />
        Your browser does not support the video tag.
      </video>

      {/* Overlay (only when not playing) */}
      {!isPlaying && (
        <div
          onClick={handlePlay}
          className="absolute inset-0 bg-black/40 flex items-center justify-center cursor-pointer transition hover:bg-black/50"
        >
          <div className="w-20 h-20 bg-white/90 rounded-full flex items-center justify-center">
            ▶
          </div>
        </div>
      )}
    </div>
  );
}

