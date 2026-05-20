<div class="mb-3">
    <label class="form-label">Title *</label>
    <input type="text" name="title" value="{{ old('title', $mentor->title ?? '') }}" class="form-control" required>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Domain *</label>
        <input type="text" name="domain" value="{{ old('domain', $mentor->domain ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Region *</label>
        <input type="text" name="region" value="{{ old('region', $mentor->region ?? '') }}" class="form-control" required>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Country *</label>
        <input type="text" name="country" value="{{ old('country', $mentor->country ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Availability Status *</label>
        <select name="availability_status" class="form-select">
            <option value="taking" @selected(old('availability_status', $mentor->availability_status ?? 'taking') === 'taking')>Taking</option>
            <option value="not_taking" @selected(old('availability_status', $mentor->availability_status ?? 'taking') === 'not_taking')>Not taking</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Bio *</label>
    <textarea name="bio" class="form-control" rows="3" required>{{ old('bio', $mentor->bio ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Expertise Summary *</label>
    <textarea name="expertise_summary" class="form-control" rows="3" required>{{ old('expertise_summary', $mentor->expertise_summary ?? '') }}</textarea>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Max Mentees</label>
        <input type="number" name="max_mentees" value="{{ old('max_mentees', $mentor->max_mentees ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Languages</label>
        <input type="text" name="languages" value="{{ old('languages', isset($mentor) && is_array($mentor->languages ?? null) ? implode(',', $mentor->languages) : '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Skills</label>
        <input type="text" name="skills" value="{{ old('skills', isset($mentor) && is_array($mentor->skills ?? null) ? implode(',', $mentor->skills) : '') }}" class="form-control">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Certifications</label>
    <input type="text" name="certifications" value="{{ old('certifications', isset($mentor) && is_array($mentor->certifications ?? null) ? implode(',', $mentor->certifications) : '') }}" class="form-control">
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $mentor->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">Active</label>
</div>
