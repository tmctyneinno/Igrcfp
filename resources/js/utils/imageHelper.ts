export const getFullImageUrl = (imagePath: string): string => {
  if (!imagePath) return '';
  
  console.log('Image path from DB:', imagePath); // Should be like '/uploads/filename.jpg'
  console.log('API URL:', process.env.NEXT_PUBLIC_API_URL);
  
  if (imagePath.startsWith('http')) return imagePath;
  
  const baseUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000';
  const fullUrl = `${baseUrl}${imagePath}`;
  
  console.log('Constructed URL:', fullUrl); // Should be 'http://localhost:5000/uploads/filename.jpg'
  return fullUrl;
};
