import React, { useRef, useState } from "react";

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