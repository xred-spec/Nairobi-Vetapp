export const buildQuery = (params: Record<string, any> = {}) => {
  const clean = Object.fromEntries(
    Object.entries(params).filter(([_, v]) => v !== '' && v !== false && v != null)
  ) as Record<string, string>;

  return new URLSearchParams(clean).toString();
};
